<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Models\Campus;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'name',
        'type',
        'value',
        'code',
        'start_date',
        'end_date',
        'applies_to',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
