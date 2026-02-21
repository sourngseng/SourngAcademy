<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['campus_id', 'name', 'translations', 'sessions', 'is_active'];

    protected $casts = [
        'sessions' => 'array',
        'translations' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get translated name
     */
    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['name'] ?? $this->translations[config('app.fallback_locale')]['name'] ?? $this->name;
    }

    public function campus()
    {
        return $this->belongsTo(\App\Modules\Core\Models\Campus::class);
    }
}
