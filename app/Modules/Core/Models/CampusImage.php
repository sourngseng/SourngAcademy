<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'image_path',
        'order',
        'is_thumbnail',
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
        'order' => 'integer',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
