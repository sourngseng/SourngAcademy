<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'image_path',
        'order',
        'is_thumbnail'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
