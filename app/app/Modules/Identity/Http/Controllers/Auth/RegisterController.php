<?php

namespace App\Modules\Identity\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Actions\RegisterUserAction;
use App\Modules\Identity\Http\Requests\RegisterRequest;
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

        return view('auth.register', ['role' => $role]);
    }

    public function store(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $user = $action->execute($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return match ($request->validated('role')) {
            'clinic' => redirect()->route('clinic.onboarding'),
            'supplier' => redirect()->route('supplier.onboarding'),
            default => redirect()->route('patient.dashboard'),
        };
    }
}
