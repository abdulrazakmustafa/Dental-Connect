<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Patient\Actions\EnrollPatientWithClinicAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClinicEnrollmentController extends Controller
{
    public function store(Request $request, Clinic $clinic, EnrollPatientWithClinicAction $action): RedirectResponse
    {
        abort_unless($clinic->isVerified() && $clinic->is_active, 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $action->execute($request->user(), $clinic, $data);

        return redirect()->route('clinics.show', $clinic)->with('status', "You're enrolled with {$clinic->name}. You can now request an appointment.");
    }
}
