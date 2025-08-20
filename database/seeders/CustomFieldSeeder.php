<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'location',
                'field_type_id' => 1,
                'display_name' => 'Location',
                'description' => 'Location of the contact',
                'is_required' => 1,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'birthday',
                'field_type_id' => 4,
                'display_name' => 'Birthday',
                'description' => 'Birthday of the contact',
                'is_required' => 1,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'meeting_time',
                'field_type_id' => 5,
                'display_name' => 'Meeting Time',
                'description' => 'Time of the meeting with the contact',
                'is_required' => 0,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'age',
                'field_type_id' => 2,
                'display_name' => 'Age',
                'description' => 'Age of the contact',
                'is_required' => 1,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'highest_education',
                'field_type_id' => 3,
                'display_name' => 'Highest Education',
                'description' => 'Highest education of the contact',
                'is_required' => 0,
                'options' => ['High School', 'Bachelors', 'Masters', 'PhD'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'notes',
                'field_type_id' => 6,
                'display_name' => 'Notes',
                'description' => 'Notes about the contact',
                'is_required' => 0,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'is_subscribed',
                'field_type_id' => 8,
                'display_name' => 'Is Subscribed',
                'description' => 'Is the contact subscribed to the newsletter',
                'is_required' => 0,
                'options' => ['Yes', 'No'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'preferred_contact_method',
                'field_type_id' => 7,
                'display_name' => 'Preferred Contact Method',
                'description' => 'Preferred contact method of the contact',
                'is_required' => 0,
                'options' => ['Email', 'Phone', 'SMS'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        foreach ($data as $item) {

            $allowedFields = [
                'name',
                'field_type_id',
                'display_name',
                'description',
                'is_required',
                'created_at',
                'updated_at',
            ];

            $filteredItem = array_filter(
                $item,
                fn($key) => in_array($key, $allowedFields),
                ARRAY_FILTER_USE_KEY
            );

            $customField = CustomField::create($filteredItem);

            if (isset($item['options'])) {
                foreach ($item['options'] as $value) {
                    $customField->options()->create([
                        'value' => preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', strtolower($value))),
                        'display_text' => $value,
                    ]);
                }
            }
        }
    }
}
