<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'icon',
        'show_in_footer',
        'show_in_header',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'show_in_footer' => 'boolean',
        'show_in_header' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get available languages from language files
     */
    public static function getAvailableLanguages(): array
    {
        $langPath = resource_path('lang');
        $languages = [];
        
        $files = File::glob($langPath . '/*.json');
        
        foreach ($files as $file) {
            $locale = basename($file, '.json');
            $languages[$locale] = [
                'name' => config('app.available_locales')[$locale]['name'] ?? strtoupper($locale),
                'flag' => config('app.available_locales')[$locale]['flag'] ?? '🌐',
            ];
        }
        
        return $languages;
    }

    /**
     * Get title for specific locale
     */
    public function getTitle($locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $titles = $this->title;
        
        if (is_string($titles)) {
            $titles = json_decode($titles, true) ?? ['en' => $titles];
        }
        
        return $titles[$locale] ?? $titles['en'] ?? null;
    }

    /**
     * Get content for specific locale
     */
    public function getContent($locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $contents = $this->content;
        
        if (is_string($contents)) {
            $contents = json_decode($contents, true) ?? ['en' => $contents];
        }
        
        return $contents[$locale] ?? $contents['en'] ?? null;
    }

    /**
     * Get localized title based on current locale
     */
    public function getLocalizedTitleAttribute(): ?string
    {
        return $this->getTitle();
    }

    /**
     * Get localized content based on current locale
     */
    public function getLocalizedContentAttribute(): ?string
    {
        return $this->getContent();
    }

    /**
     * Scope for active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for footer pages
     */
    public function scopeFooter($query)
    {
        return $query->where('show_in_footer', true);
    }

    /**
     * Scope for header pages
     */
    public function scopeHeader($query)
    {
        return $query->where('show_in_header', true);
    }

    /**
     * Get footer pages ordered by sort_order
     */
    public static function getFooterPages()
    {
        return self::active()->footer()->orderBy('sort_order')->get();
    }

    /**
     * Get header pages ordered by sort_order
     */
    public static function getHeaderPages()
    {
        return self::active()->header()->orderBy('sort_order')->get();
    }

    /**
     * Find page by slug
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->active()->first();
    }
}
