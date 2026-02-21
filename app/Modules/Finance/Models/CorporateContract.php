<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Models\Campus;

class CorporateContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'campus_id',
        'name',
        'discount_percentage',
        'max_students',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
