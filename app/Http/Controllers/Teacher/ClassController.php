<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * Display a listing of the teacher's classes.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch Teacher's ClassSubjects
        $classSubjects = ClassSubject::where('teacher_id', $user->id)
            ->with(['class.campus', 'class.program', 'subject', 'classroom'])
            ->get()
            ->groupBy('class_id');

        return view('teacher.my_classes.index', compact('classSubjects'));
    }

    /**
     * Display the specified class with its students.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Ensure the teacher is assigned to this class
        $assigned = ClassSubject::where('class_id', $id)
            ->where('teacher_id', $user->id)
            ->exists();

        if (!$assigned) {
            abort(403, 'Unauthorized action.');
        }

        $class = ClassModel::with(['students', 'campus', 'program', 'classSubjects.subject', 'classSubjects.classroom'])
            ->findOrFail($id);

        return view('teacher.my_classes.show', compact('class'));
    }

    /**
     * Display the class timetable.
     */
    
    public function timetable($id)
    {
        $user = Auth::user();
        
        // Ensure the teacher is assigned to this class
        $assigned = ClassSubject::where('class_id', $id)
            ->where('teacher_id', $user->id)
            ->exists();

        if (!$assigned) {
            abort(403, 'Unauthorized action.');
        }

        $class = ClassModel::with(['classSubjects.subject', 'classSubjects.teacher', 'classSubjects.classroom', 'semester', 'campus', 'program', 'shift'])->findOrFail($id);
        
        return view('admin.classes.timetable-print', compact('class'));
    }
}
