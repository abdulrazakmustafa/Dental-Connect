<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Service;
use App\Modules\Clinic\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public verified clinic directory (PRD §7.2/§11 hot-query strategy).
 * Filters use normalized foreign keys, never free-text paragraph matching.
 *
 * Doubles as the "choose your clinic" enrollment screen for a patient who
 * has not enrolled anywhere yet (mockup P04) — same query/list, different
 * hero framing, decided from the viewer's own enrollment count.
 */
class ClinicDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $clinics = Clinic::query()
            ->select(['id', 'public_id', 'slug', 'name', 'logo_path', 'primary_region_id', 'verification_status', 'is_active'])
            ->where('verification_status', Clinic::STATUS_APPROVED)
            ->where('is_active', true)
            ->withCount(['reviews' => fn ($q) => $q->where('moderation_status', 'published')])
            ->withAvg(['reviews' => fn ($q) => $q->where('moderation_status', 'published')], 'rating')
            ->with(['primaryLocation:id,clinic_id,region,city,area', 'services:id,name'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', $request->string('q').'%'))
            ->when($request->filled('service'), fn ($q) => $q->whereHas('services', fn ($s) => $s->where('services.id', $request->integer('service'))))
            ->when($request->filled('specialty'), fn ($q) => $q->whereHas('specialties', fn ($s) => $s->where('specialties.id', $request->integer('specialty'))))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $isPatient = auth()->check() && auth()->user()->hasRole('patient');
        $isChoosingFirstClinic = $isPatient && auth()->user()->clinicPatients()->count() === 0;

        return view($isPatient ? 'patient.clinics.index' : 'clinic.directory', [
            'clinics' => $clinics,
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
            'isChoosingFirstClinic' => $isChoosingFirstClinic,
        ]);
    }

    public function show(Clinic $clinic): View
    {
        abort_unless(
            ($clinic->isVerified() && $clinic->is_active) || (auth()->check() && auth()->user()->can('view', $clinic)),
            404
        );

        $clinic->load(['primaryLocation', 'services', 'specialties', 'dentists' => fn ($q) => $q->where('status', 'active')->orderBy('sort_order')]);
        $clinic->loadCount(['reviews' => fn ($q) => $q->where('moderation_status', 'published')]);
        $clinic->loadAvg(['reviews' => fn ($q) => $q->where('moderation_status', 'published')], 'rating');

        $clinicPatient = auth()->check()
            ? $clinic->patients()->where('user_id', auth()->id())->first()
            : null;

        $isPatient = auth()->check() && auth()->user()->hasRole('patient');

        return view($isPatient ? 'patient.clinics.show' : 'clinic.profile', ['clinic' => $clinic, 'clinicPatient' => $clinicPatient]);
    }
}
