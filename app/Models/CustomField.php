<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = ['name', 'field_type_id', 'display_name', 'description', 'is_required'];
    
    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
    
    public function contactValues()
    {
        return $this->hasMany(ContactCustomField::class);
    }

    public function options()
    {
        return $this->hasMany(CustomFieldOptions::class);
    }
}
