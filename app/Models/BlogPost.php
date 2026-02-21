<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image_path',
        'is_published',
        'published_at',
        'views_count',
        'reader_locations',
        'reader_device_types',
        'translations',
        'default_lang',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'reader_locations' => 'array',
        'reader_device_types' => 'array',
        'translations' => 'array',
    ];
    
    /**
     * Get translated title
     */
    public function getTranslatedTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['title'] ?? $this->title;
    }
    
    /**
     * Get translated content
     */
    public function getTranslatedContent($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['content'] ?? $this->content;
    }
    
    /**
     * Get translated excerpt
     */
    public function getTranslatedExcerpt($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations[$locale]['excerpt'] ?? $this->excerpt;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
