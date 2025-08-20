<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    protected $fillable = ['name', 'data_type'];
    
    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }
}
