<?php

namespace App\Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DentistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // controller scopes to the caller's own clinic
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:160'],
            'license_number' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive'],
            'specialties' => ['nullable', 'array'],
            'specialties.*' => ['exists:specialties,id'],
        ];
    }
}
