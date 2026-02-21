<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'image'];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
