<?php

namespace App\Modules\Academic\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'name',
        'slug',
        'code',
        'feature_image',
        'description',
        'mission',
        'vision',
        'goal',
        'head_of_department_id',
        'email',
        'phone',
        'working_hours',
        'translations',
    ];

    protected $casts = [
        'translations' => 'array',
    ];

    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['name'] ?? $this->translations[config('app.fallback_locale')]['name'] ?? $this->name;
    }

    public function getTranslatedDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['description'] ?? $this->translations[config('app.fallback_locale')]['description'] ?? $this->description;
    }

    public function getTranslatedMission($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['mission'] ?? $this->translations[config('app.fallback_locale')]['mission'] ?? $this->mission;
    }

    public function getTranslatedVision($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['vision'] ?? $this->translations[config('app.fallback_locale')]['vision'] ?? $this->vision;
    }

    public function getTranslatedGoal($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['goal'] ?? $this->translations[config('app.fallback_locale')]['goal'] ?? $this->goal;
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function headOfDepartment()
    {
        return $this->belongsTo(User::class, 'head_of_department_id');
    }
}
