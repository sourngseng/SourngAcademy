<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Models\Campus;

class Classroom extends Model
{
    protected $fillable = [
        'campus_id',
        'room_number',
        'name',
        'building',
        'floor',
        'capacity',
        'type',
        'description',
        'image',
        'status',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
        'status' => 'boolean',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get translated name
     */
    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['name'] ?? $this->name;
    }

    /**
     * Get translated description
     */
    public function getTranslatedDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['description'] ?? $this->description;
    }
}
