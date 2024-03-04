<?php

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'array|min:1',
            'name.*' => 'string|min:2|max:255',
            'description' => 'array|min:1',
            'description.*' => 'string|min:4',
        ];
    }
}
