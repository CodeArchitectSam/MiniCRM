<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldOptions extends Model
{
    protected $fillable = ['custom_field_id', 'value', 'display_text'];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
