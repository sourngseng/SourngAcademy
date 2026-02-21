<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Models\Campus;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'room_number',
        'capacity',
        'type',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
