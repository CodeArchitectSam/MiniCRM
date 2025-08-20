<?php

namespace App\Services;

use App\Models\CustomField;

class CustomFieldService
{

    public function getCustomFields()
    {
        return CustomField::with('fieldType')->orderBy('id', 'desc')->paginate(config('crm.rows_per_page'));
    }

    public function createCustomField(array $data): CustomField
    {
        $customField = CustomField::create($data);

        if (!empty($data['options'])) {
            $this->saveFieldOptions($customField, $data['options']);
        }

        return $customField;
    }

    public function saveFieldOptions(CustomField $customField, string $options): void
    {
        $options = explode("\n", $options);
        $options = array_map('trim', $options);

        foreach ($options as $value) {
            $customField->options()->create([
                'value' => preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', strtolower($value))),
                'display_text' => $value,
            ]);
        }
    }

    public function updateCustomField(int $id, array $data): CustomField
    {
        $customField = CustomField::findOrFail($id);
        $customField->update($data);
        
        return $customField;
    }
}