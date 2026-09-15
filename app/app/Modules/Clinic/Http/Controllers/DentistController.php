<?php

namespace App\Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Http\Requests\DentistRequest;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Clinic\Models\Dentist;
use App\Modules\Clinic\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DentistController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = $this->currentClinic($request);

        $dentists = $clinic->dentists()->with('specialties')->orderBy('sort_order')->orderBy('full_name')->get();

        return view('clinic.dentists.index', ['clinic' => $clinic, 'dentists' => $dentists]);
    }

    public function create(Request $request): View
    {
        return view('clinic.dentists.form', [
            'clinic' => $this->currentClinic($request),
            'dentist' => new Dentist(['status' => 'active']),
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(DentistRequest $request): RedirectResponse
    {
        $clinic = $this->currentClinic($request);

        $data = $request->validated();
        $dentist = $clinic->dentists()->create([
            'full_name' => $data['full_name'],
            'license_number' => $data['license_number'] ?? null,
            'bio' => $data['bio'] ?? null,
            'status' => $data['status'],
        ]);
        $dentist->specialties()->sync($data['specialties'] ?? []);

        return redirect()->route('clinic.dentists.index')->with('status', 'Dentist added.');
    }

    public function edit(Request $request, Dentist $dentist): View
    {
        $this->authorize('update', $dentist);
        $dentist->load('specialties');

        return view('clinic.dentists.form', [
            'clinic' => $dentist->clinic,
            'dentist' => $dentist,
            'specialties' => Specialty::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(DentistRequest $request, Dentist $dentist): RedirectResponse
    {
        $this->authorize('update', $dentist);

        $data = $request->validated();
        $dentist->update([
            'full_name' => $data['full_name'],
            'license_number' => $data['license_number'] ?? null,
            'bio' => $data['bio'] ?? null,
            'status' => $data['status'],
        ]);
        $dentist->specialties()->sync($data['specialties'] ?? []);

        return redirect()->route('clinic.dentists.index')->with('status', 'Dentist updated.');
    }

    private function currentClinic(Request $request): Clinic
    {
        $clinic = $request->user()->ownedClinics()->first()
            ?? $request->user()->clinicStaffMemberships()->with('clinic')->first()?->clinic;

        abort_if(! $clinic, 404, 'No clinic is associated with this account yet.');

        return $clinic;
    }
}
