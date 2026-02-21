<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'image_path',
        'is_active',
    ];

    // Alias for consistency
    public function getImageAttribute()
    {
        return $this->image_path;
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image_path'] = $value;
    }
}
