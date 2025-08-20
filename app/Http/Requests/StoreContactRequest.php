<?php

namespace App\Http\Requests;

use App\Models\CustomField;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:10',
            'profile_image_path' => 'nullable|image|max:2048',
            'additional_file_path' => 'nullable|file|max:2048',
        ];

        $customFields = CustomField::with('fieldType:id,name', 'options:id,custom_field_id,display_text')
            ->orderBy('field_type_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($customFields as $customField) {
            $isRequired = $customField->is_required ? 'required' : 'nullable';
            switch ($customField->fieldType->name) {
                case 'Text':
                    $rules[$customField->name] = $isRequired . '|string|max:255';
                    break;
                case 'Number':
                    $rules[$customField->name] = $isRequired . '|numeric';
                    break;
                case 'Dropdown':
                    $rules[$customField->name] = $isRequired . '|string|max:255';
                    break;
                case 'Date':
                    $rules[$customField->name] = $isRequired . '|date';
                    break;
                case 'Time':
                    $rules[$customField->name] = $isRequired . '|date_format:H:i';
                    break;
                case 'Long Text':
                    $rules[$customField->name] = $isRequired . '|string';
                    break;
                case 'CheckBox':
                    $rules[$customField->name] = $isRequired . '|array';
                    break;
                case 'Radio Button':
                    $rules[$customField->name] = $isRequired;
                    break;
                default:
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'profile_image_path.image' => 'The profile image must be an image (jpg/png).',
            'profile_image_path.max' => 'The profile image must not exceed 2MB.',
            'additional_file_path.file' => 'The additional file must be a valid file.',
            'additional_file_path.max' => 'The additional file must not exceed 2MB.',
        ];
    }
}
