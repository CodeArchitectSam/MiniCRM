<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('field_types')->insert([
            [
                'name' => 'Text',
                'data_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Number',
                'data_type' => 'number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dropdown',
                'data_type' => 'enum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Date',
                'data_type' => 'date',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Time',
                'data_type' => 'datetime',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Long Text',
                'data_type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CheckBox',
                'data_type' => 'enum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Radio Button',
                'data_type' => 'enum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
