<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Campus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_km',
        'code',
        'slug',
        'short_name',
        'feature_image',
        'payment_qr_image',
        'address',
        'phone',
        'email',
        'description',
        'latitude',
        'longitude',
        'is_active',
        'translations',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'latitude'     => 'decimal:8',
        'longitude'    => 'decimal:8',
        'translations' => 'array',
    ];

    /**
     * Get translated name for a given locale.
     * Falls back to name_en or name_km if translation not found.
     */
    public function getTranslatedName($locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        // Check translations JSON first
        if (!empty($this->translations[$locale]['name'])) {
            return $this->translations[$locale]['name'];
        }

        // Legacy: direct columns
        if ($locale === 'km' && !empty($this->name_km)) {
            return $this->name_km;
        }
        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }

        // Fallback chain
        return $this->name_en ?? $this->name_km ?? '';
    }

    /**
     * Get translated description for a given locale.
     */
    public function getTranslatedDescription($locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if (!empty($this->translations[$locale]['description'])) {
            return $this->translations[$locale]['description'];
        }

        return $this->description ?? '';
    }

    /**
     * Get translated address for a given locale.
     */
    public function getTranslatedAddress($locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if (!empty($this->translations[$locale]['address'])) {
            return $this->translations[$locale]['address'];
        }

        return $this->address ?? '';
    }

    /**
     * The `name` accessor returns the translated name for the current locale.
     */
    public function getNameAttribute(): string
    {
        return $this->getTranslatedName();
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function faculties()
    {
        return $this->hasMany(\App\Modules\Academic\Models\Faculty::class);
    }

    public function images()
    {
        return $this->hasMany(CampusImage::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(CampusPaymentMethod::class)->orderBy('sort_order')->orderBy('id');
    }
}
