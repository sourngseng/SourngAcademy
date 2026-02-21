<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Models\Campus;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'name',
        'provider',
        'percentage',
        'min_gpa_renewal',
        'description',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
