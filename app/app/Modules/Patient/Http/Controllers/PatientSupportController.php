<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "Support" tab: the patient's own clinic (details, contact, services, dentists, reviews)
 * followed by information about the app itself.
 */
class PatientSupportController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $clinicPatient = $request->user()->clinicPatients()->with('clinic')->orderBy('id')->first();

        // A patient with no enrollment yet still goes through the clinic picker.
        if (! $clinicPatient) {
            return redirect()->route('clinics.index');
        }

        $clinic = $clinicPatient->clinic;
        $clinic->load(['primaryLocation', 'services', 'specialties', 'dentists' => fn ($q) => $q->where('status', 'active')->orderBy('sort_order')]);
        $clinic->loadCount(['reviews' => fn ($q) => $q->where('moderation_status', 'published')]);
        $clinic->loadAvg(['reviews' => fn ($q) => $q->where('moderation_status', 'published')], 'rating');

        return view('patient.support', [
            'clinic' => $clinic,
            'clinicPatient' => $clinicPatient,
            'reviews' => $clinic->reviews()->where('moderation_status', 'published')->latest()->limit(3)->get(),
        ]);
    }
}
