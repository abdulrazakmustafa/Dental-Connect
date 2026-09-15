<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $activeClinicPatient = $user->clinicPatients()->with('clinic:id,name')->latest()->first();

        return view('patient.profile.index', [
            'user' => $user,
            'activeClinicPatient' => $activeClinicPatient,
        ]);
    }
}
