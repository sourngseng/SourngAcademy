<?php

namespace App\Modules\Academic\Models;

use App\Models\User;
use App\Modules\Core\Models\Campus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'name',
        'slug',
        'code',
        'dean_name',
        'feature_image',
        'icon',
        'description',
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

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
