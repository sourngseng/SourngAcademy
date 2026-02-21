<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'academic_year_id',
        'semester_id',
        'name',
        'amount',
        'currency',
        'type',
        'description',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
