<?php

namespace App\Modules\Identity\Http\Requests;

use App\Modules\Clinic\Models\Clinic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'in:patient,clinic,supplier'],
            'name' => ['required', 'string', 'max:160'],
            'organization_name' => ['required_unless:role,patient', 'nullable', 'string', 'max:160'],
            'clinic_id' => [
                'required_if:role,patient',
                'nullable',
                'string',
                Rule::exists('clinics', 'public_id')->where('verification_status', Clinic::STATUS_APPROVED)->where('is_active', true),
            ],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'terms.accepted' => 'You must agree to the Terms & Privacy Policy.',
            'clinic_id.required_if' => 'Please choose a clinic to enroll with.',
            'clinic_id.exists' => 'Please choose a verified clinic from the list.',
        ];
    }
}
