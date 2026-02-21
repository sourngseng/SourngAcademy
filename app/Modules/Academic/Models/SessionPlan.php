<?php

namespace App\Modules\Academic\Models;

use App\Models\User;
use App\Modules\Core\Models\Campus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'program_id',
        'subject_id',
        'academic_year_id',
        'semester_id',
        'parent_id',
        'title',
        'description',
        'duration',
        'duration_unit',
        'attachment',
        'video_url',
        'status',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(SessionPlan::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SessionPlan::class, 'parent_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
