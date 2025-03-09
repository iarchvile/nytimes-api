<?php

namespace App\Http\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BestSellersHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age-group' => 'nullable|string',
            'author' => 'nullable|string',
            'contributor' => 'nullable|string',
            'isbn' => ['nullable', 'string', 'regex:/^(\d{10}(;\d{10})*|\d{13}(;\d{13})*)$/'],
            'offset' => 'nullable|integer|min:0|multiple_of:20',
            'price' => ['nullable', 'string', 'regex:/^\d+(\.\d{1,2})?$/'],
            'publisher' => 'nullable|string',
            'title' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.regex' => 'The ISBN must be a 10 or 13 digit number, or multiple ISBNs separated by semicolons.',
            'offset.multiple_of' => 'The offset must be a multiple of 20.',
            'price.regex' => 'The price must be a valid decimal number (e.g., 10.99).',
        ];
    }
}
