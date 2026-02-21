<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Core\Models\Campus;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'campus_id',
        'program_id',
        'subject_id',
        'teacher_id',
        'semester_id',
        'classroom_id',
        'name',
        'code',
        'schedule_json',
        'max_students',
        'status',
    ];

    protected $casts = [
        'schedule_json' => 'json',
    ];

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class);
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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function classSubjects()
    {
        return $this->hasMany(\App\Models\ClassSubject::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id')
            ->withPivot('status', 'grade_points', 'grade_letter')
            ->withTimestamps();
    }
}
