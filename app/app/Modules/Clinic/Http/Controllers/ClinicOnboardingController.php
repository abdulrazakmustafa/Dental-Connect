<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Service;
use App\Modules\Clinic\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Clinic profile completion + verification submission (PRD §7.3, §26
 * "Clinic verification" workflow steps 11-18). Approval itself is an admin
 * action (ClinicVerificationController) — this controller only submits.
 *
 * This same form is reused after approval for ordinary profile edits
 * (services/pricing/description/location) — those must NOT silently kick
 * an already-approved clinic back into "pending verification"; only a
 * clinic still in draft/changes_requested actually (re)submits.
 */
class ClinicOnboardingController extends Controller
{
    private const SUBMITTABLE_STATUSES = [Clinic::STATUS_DRAFT, Clinic::STATUS_CHANGES_REQUESTED];

    public function create(Request $request): View
    {
        $clinic = $request->user()->ownedClinics()->firstOrFail();
        $clinic->load(['primaryLocation', 'services', 'specialties']);

        return view('clinic.onboarding', [
            'clinic' => $clinic,
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(),
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(),
            'isSubmission' => in_array($clinic->verification_status, self::SUBMITTABLE_STATUSES, true),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $clinic = $request->user()->ownedClinics()->firstOrFail();

        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:2000'],
            'address_line' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
            'area' => ['nullable', 'string', 'max:80'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['exists:specialties,id'],
        ]);

        $isSubmission = in_array($clinic->verification_status, self::SUBMITTABLE_STATUSES, true);

        DB::transaction(function () use ($clinic, $data, $isSubmission) {
            $clinic->update(['description' => $data['description'] ?? null]);

            $clinic->locations()->updateOrCreate(
                ['clinic_id' => $clinic->id, 'is_primary' => true],
                [
                    'address_line' => $data['address_line'],
                    'region' => $data['region'],
                    'city' => $data['city'],
                    'area' => $data['area'] ?? null,
                ]
            );

            // Preserve per-service pricing for services that stay selected;
            // only detach removed ones and attach newly-added ones (sync()
            // would otherwise wipe the price pivot on every save).
            $selectedServiceIds = $data['services'] ?? [];
            $currentServiceIds = $clinic->services()->pluck('services.id')->all();
            $clinic->services()->detach(array_diff($currentServiceIds, $selectedServiceIds));
            $clinic->services()->syncWithoutDetaching(array_diff($selectedServiceIds, $currentServiceIds));

            $clinic->specialties()->sync($data['specialties'] ?? []);

            if ($isSubmission) {
                $clinic->update([
                    'verification_status' => Clinic::STATUS_SUBMITTED,
                    'profile_completion_percent' => 80,
                ]);

                $clinic->verificationSubmissions()->create([
                    'verifiable_type' => Clinic::class,
                    'verifiable_id' => $clinic->id,
                    'status' => Clinic::STATUS_SUBMITTED,
                    'submitted_at' => now(),
                ]);
            }
        });

        return redirect()->route('clinic.dashboard')->with(
            'status',
            $isSubmission ? 'Your profile was submitted for verification.' : 'Profile updated.'
        );
    }
}
