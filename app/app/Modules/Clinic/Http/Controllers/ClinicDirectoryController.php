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
 */
class ClinicDirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $clinics = Clinic::query()
            ->select(['id', 'public_id', 'slug', 'name', 'logo_path', 'primary_region_id', 'verification_status', 'is_active'])
            ->where('verification_status', Clinic::STATUS_APPROVED)
            ->where('is_active', true)
            ->with(['primaryLocation:id,clinic_id,region,city,area'])
            ->when($request->filled('service'), fn ($q) => $q->whereHas('services', fn ($s) => $s->where('services.id', $request->integer('service'))))
            ->when($request->filled('specialty'), fn ($q) => $q->whereHas('specialties', fn ($s) => $s->where('specialties.id', $request->integer('specialty'))))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('clinic.directory', [
            'clinics' => $clinics,
            'services' => Service::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function show(Clinic $clinic): View
    {
        abort_unless(
            ($clinic->isVerified() && $clinic->is_active) || (auth()->check() && auth()->user()->can('view', $clinic)),
            404
        );

        $clinic->load(['primaryLocation', 'services', 'specialties', 'dentists' => fn ($q) => $q->where('status', 'active')->orderBy('sort_order')]);

        $clinicPatient = auth()->check()
            ? $clinic->patients()->where('user_id', auth()->id())->first()
            : null;

        return view('clinic.profile', ['clinic' => $clinic, 'clinicPatient' => $clinicPatient]);
    }
}
