<?php

namespace App\Models;

use App\Modules\Core\Models\Campus;
use App\Modules\Academic\Models\Semester;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'campus_id',
        'shift_id',
        'semester_id',
        'program_id',
        'name',
        'code',
        'max_students',
        'status',
        'description',
        'image'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['classSubjects']; // Eager load subjects

    public function program()
    {
        return $this->belongsTo(\App\Modules\Academic\Models\Program::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    // Helper to get subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id')
                    ->withPivot('teacher_id', 'classroom_id', 'schedule_json')
                    ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id')
                    ->withPivot('status', 'grade_points', 'grade_letter', 'enrolled_at')
                    ->withTimestamps();
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'class_id');
    }
}
