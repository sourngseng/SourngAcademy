<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'link_url',
        'button_text',
        'order',
        'is_active',
        'show_title',
        'show_subtitle',
        'show_description',
        'show_button',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_title' => 'boolean',
        'show_subtitle' => 'boolean',
        'show_description' => 'boolean',
        'show_button' => 'boolean',
        'order' => 'integer',
        'title' => 'array',
        'subtitle' => 'array',
        'description' => 'array',
        'button_text' => 'array',
    ];

    /**
     * Get translated content for a specific field.
     * Fallback to 'en' or first available key if current locale is missing.
     */
    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $value = $this->$field;

        if (is_array($value)) {
            return $value[$locale] ?? ($value['en'] ?? ($value[array_key_first($value)] ?? null));
        }
        
        // Handle legacy string data if any
        return is_string($value) ? $value : null;
    }
}
