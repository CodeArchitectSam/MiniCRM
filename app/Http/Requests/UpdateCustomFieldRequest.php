<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomFieldRequest extends FormRequest
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
        $rules = [
            'display_name' => 'required|string|max:100',
            'name' => 'required|string|max:100|unique:custom_fields,name,' . $this->id,
            'description' => 'nullable|string|max:255',
            'is_required' => 'boolean',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'display_name.required' => 'Name is required.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'description' => $this->description ?: null,
            'is_required' => $this->boolean('is_required'),
        ]);
    }
}
