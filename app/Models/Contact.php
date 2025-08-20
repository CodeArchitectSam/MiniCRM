<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'gender', 
        'profile_image_path', 
        'additional_file_path',
        'is_active', 
        'merged_into'
    ];
    
    public function customFields()
    {
        return $this->belongsToMany(CustomField::class, 'contact_custom_fields')
            ->withPivot('value')
            ->withTimestamps();
    }
    
    public function mergedContacts()
    {
        return $this->hasMany(Contact::class, 'merged_into');
    }
    
    public function masterContact()
    {
        return $this->belongsTo(Contact::class, 'merged_into');
    }
}
