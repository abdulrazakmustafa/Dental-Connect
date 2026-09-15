<?php

namespace App\Modules\Supplier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // controller-level policy check handles authorization
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'brand' => ['nullable', 'string', 'max:120'],
            'reference_code' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:3000'],
            'specifications' => ['nullable', 'string', 'max:3000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'price_unit' => ['nullable', 'string', 'max:40'],
            'price_visible' => ['boolean'],
            'is_available' => ['boolean'],
        ];
    }
}
