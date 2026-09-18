<?php

namespace App\Modules\Identity\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Clinic\Models\Clinic;
use App\Modules\Identity\Actions\RegisterUserAction;
use App\Modules\Identity\Http\Requests\RegisterRequest;
use App\Modules\Patient\Actions\EnrollPatientWithClinicAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        $role = in_array($request->query('role'), ['patient', 'clinic', 'supplier'], true)
            ? $request->query('role')
            : 'patient';

        $clinics = Clinic::query()
            ->where('verification_status', Clinic::STATUS_APPROVED)
            ->where('is_active', true)
            ->with('primaryLocation:id,clinic_id,region,city,area')
            ->orderBy('name')
            ->get(['id', 'public_id', 'name', 'logo_path']);

        return view('auth.register', ['role' => $role, 'clinics' => $clinics]);
    }

    public function store(RegisterRequest $request, RegisterUserAction $action, EnrollPatientWithClinicAction $enroll): RedirectResponse
    {
        $user = $action->execute($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->validated('role') === 'patient') {
            $clinic = Clinic::where('public_id', $request->validated('clinic_id'))->firstOrFail();
            [$firstName, $lastName] = array_pad(explode(' ', trim($request->validated('name')), 2), 2, '');

            $enroll->execute($user, $clinic, [
                'first_name' => $firstName ?: $request->validated('name'),
                'last_name' => $lastName,
            ]);
        }

        return match ($request->validated('role')) {
            'clinic' => redirect()->route('clinic.onboarding'),
            'supplier' => redirect()->route('supplier.onboarding'),
            default => redirect()->route('patient.dashboard'),
        };
    }
}
