<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'description',
        'code',
        'degree_level_id',
        'duration_years',
        'total_credits',
        'feature_image',
        'is_popular',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
        'is_popular' => 'boolean',
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

    public function degreeLevel()
    {
        return $this->belongsTo(DegreeLevel::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function images()
    {
        return $this->hasMany(ProgramImage::class);
    }

    public function programSubjects()
    {
        return $this->hasMany(ProgramSubject::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'program_subjects');
    }
}
