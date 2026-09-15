<?php

namespace App\Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'title' => ['nullable', 'string', 'max:80'],
            'role' => ['required', 'in:clinic_admin,clinic_staff'],
        ];
    }
}
