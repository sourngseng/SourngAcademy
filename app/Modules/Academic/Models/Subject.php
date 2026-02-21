<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'code',
        'name',
        'description',
        'credit',
        'type',
        'feature_image',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    /**
     * Get translated name
     */
    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['name'] ?? $this->translations[config('app.fallback_locale')]['name'] ?? $this->name;
    }

    /**
     * Get translated description
     */
    public function getTranslatedDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['description'] ?? $this->translations[config('app.fallback_locale')]['description'] ?? $this->description;
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function images()
    {
        return $this->hasMany(SubjectImage::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'subject_id');
    }

    public function sessionPlans()
    {
        return $this->hasMany(SessionPlan::class, 'subject_id');
    }
}
