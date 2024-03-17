<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'integer'],
            'author' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the messages that apply to the error response.
     *
     * @return non-empty-array<string, string>
     */
    public function messages(): array
    {
        return [
            'price' => 'The price must be an integer.',
        ];
    }
}
