<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBookRequest extends FormRequest
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
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['asc', 'desc']),
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
            'sortBy' => "The sortBy parameter accepts only 'price' value.",
            'sortOrder' => "The sortBy parameter accepts only 'asc' and 'desc' values.",
        ];
    }
}
