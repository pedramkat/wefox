<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return non-empty-array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'sku' => ['required', 'unique:books', 'regex:/^[a-z0-9\-]+$/'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'author' => ['required'],
            'date_published' => ['required', 'date'],
        ];
    }
}
