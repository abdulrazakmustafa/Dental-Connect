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
 */
class ClinicOnboardingController extends Controller
{
    public function create(Request $request): View
    {
        $clinic = $request->user()->ownedClinics()->firstOrFail();

        return view('clinic.onboarding', [
            'clinic' => $clinic,
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(),
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(),
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

        DB::transaction(function () use ($clinic, $data) {
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

            $clinic->services()->sync($data['services'] ?? []);
            $clinic->specialties()->sync($data['specialties'] ?? []);

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
        });

        return redirect()->route('clinic.dashboard')->with('status', 'Your profile was submitted for verification.');
    }
}
