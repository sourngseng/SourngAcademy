<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'image_path',
        'order',
        'is_thumbnail',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
