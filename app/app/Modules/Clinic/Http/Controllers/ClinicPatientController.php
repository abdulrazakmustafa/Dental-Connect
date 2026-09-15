<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\ClinicPatient;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "My Patients" — clinic-scoped only (PRD §7.3/§39). Every query here is
 * filtered by the caller's own clinic id(s); ClinicPatientPolicy re-checks
 * ownership on top of this as defense in depth.
 */
class ClinicPatientController extends Controller
{
    public function index(Request $request): View
    {
        $clinicIds = $request->user()->ownedClinics()->pluck('id')
            ->merge($request->user()->clinicStaffMemberships()->pluck('clinic_id'));

        $this->authorize('viewAny', ClinicPatient::class);

        $patients = ClinicPatient::query()
            ->whereIn('clinic_id', $clinicIds)
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->string('q');
                $q->where(function ($q) use ($term) {
                    $q->where('patient_number', $term)
                        ->orWhere('first_name', 'like', "{$term}%")
                        ->orWhere('last_name', 'like', "{$term}%");
                });
            })
            ->with('assignedDentist:id,full_name')
            ->orderBy('last_name')->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('clinic.patients.index', ['patients' => $patients]);
    }

    public function show(Request $request, ClinicPatient $clinicPatient): View
    {
        $this->authorize('view', $clinicPatient);

        $clinicPatient->load(['appointments' => fn ($q) => $q->orderByDesc('preferred_date'), 'assignedDentist']);

        return view('clinic.patients.show', ['clinicPatient' => $clinicPatient]);
    }
}
