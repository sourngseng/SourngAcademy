<?php

namespace App\Models;

use App\Modules\Academic\Models\ClassModel;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $table = 'class_subjects';

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'classroom_id',
        'schedule_json',
    ];

    protected $casts = [
        'schedule_json' => 'array',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
