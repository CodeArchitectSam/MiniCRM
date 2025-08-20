<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomFieldRequest extends FormRequest
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
            'field_type_id' => 'required|exists:field_types,id',
            'description' => 'nullable|string|max:255',
            'is_required' => 'boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['name'] = [
                'required',
                'alpha_dash',
                'max:100',
                'unique:custom_fields,name'
            ];
        } else {
            $rules['name'] = [
                'required',
                'alpha_dash',
                'max:100',
                Rule::unique('custom_fields', 'name')->ignore($this->custom_field)
            ];
        }

        if ($this->field_type_id) {
            $fieldType = \App\Models\FieldType::find($this->field_type_id);
            
            if ($fieldType && in_array($fieldType->data_type, ['enum'])) {
                $rules['options'] = 'required|string';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'display_name.required' => 'Name is required.',
            'field_type_id.required' => 'Field type is required.',
            'options.required' => 'You must provide at least one option for this field type.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'description' => $this->description ?: null,
            'is_required' => $this->boolean('is_required'),
            'options' => $this->options ?? []
        ]);
    }
}
