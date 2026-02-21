<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\SessionPlan;
use App\Modules\Core\Models\Campus;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionPlanController extends Controller
{
    /**
     * Display a listing of the teacher's session plans.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Fetch subjects assigned to this teacher
        $assignedSubjectIds = ClassSubject::where('teacher_id', $user->id)
            ->pluck('subject_id')
            ->unique();

        $query = SessionPlan::with(['subject', 'author', 'children'])
            ->withCount('children')
            ->whereNull('parent_id')
            ->whereIn('subject_id', $assignedSubjectIds);

        if ($request->filled('subject_id')) $query->where('subject_id', $request->subject_id);
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // For filtering dropdown
        $assignedSubjects = Subject::whereIn('id', $assignedSubjectIds)->get();

        if ($request->ajax()) {
            return view('teacher.session-plans.partials._list', compact('sessions'))->render();
        }

        return view('teacher.session-plans.index', compact('sessions', 'assignedSubjects'));
    }

    /**
     * Show the form for creating a new session plan.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get classes assigned to teacher to find subjects/campuses
        $classes = ClassSubject::where('teacher_id', $user->id)
            ->with(['class.campus', 'subject.program'])
            ->get();

        if ($classes->isEmpty()) {
            return redirect()->route('teacher.dashboard')->with('error', 'You have no subjects assigned to create session plans.');
        }

        $campuses = $classes->pluck('class.campus')->unique('id');
        $academicYears = AcademicYear::where('status', 1)->get();

        return view('teacher.session-plans.create', compact('campuses', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'program_id' => 'required|exists:programs,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'attachment' => 'nullable|file|max:10240',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:session_plans,id',
            'duration' => 'nullable|integer|min:0',
            'duration_unit' => 'required_with:duration|in:minutes,hours',
            'video_url' => 'nullable|url|max:255',
        ]);

        // Security check: ensure teacher is assigned to this subject
        $isAssigned = ClassSubject::where('teacher_id', $user->id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$isAssigned) {
            return back()->withErrors(['subject_id' => 'You are not assigned to this subject.'])->withInput();
        }

        $data = $request->all();
        $data['created_by'] = $user->id;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'session_plan_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/session_plans'), $fileName);
            $data['attachment'] = 'uploads/session_plans/' . $fileName;
        }

        SessionPlan::create($data);

        return redirect()->route('teacher.session-plans.index')->with('success', 'Session Plan created successfully.');
    }

    /**
     * Show the form for editing.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $sessionPlan = SessionPlan::where('created_by', $user->id)->findOrFail($id);
        
        $classes = ClassSubject::where('teacher_id', $user->id)->with(['class.campus', 'subject.program'])->get();
        $campuses = $classes->pluck('class.campus')->unique('id');
        $academicYears = AcademicYear::all();
        
        $programs = Program::whereHas('department.faculty', function($q) use($sessionPlan) {
            $q->where('campus_id', $sessionPlan->campus_id);
        })->get();
        
        $subjects = $sessionPlan->program->subjects;
        
        $semesters = [];
        if($sessionPlan->academic_year_id) {
            $semesters = Semester::where('academic_year_id', $sessionPlan->academic_year_id)->get();
        }

        $parents = SessionPlan::where('subject_id', $sessionPlan->subject_id)
            ->where('id', '!=', $sessionPlan->id)
            ->whereNull('parent_id')
            ->get();
        
        return view('teacher.session-plans.edit', compact('sessionPlan', 'campuses', 'academicYears', 'programs', 'subjects', 'semesters', 'parents'));
    }

    /**
     * Update the resource.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $sessionPlan = SessionPlan::where('created_by', $user->id)->findOrFail($id);

        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'program_id' => 'required|exists:programs,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'attachment' => 'nullable|file|max:10240',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:session_plans,id',
            'duration' => 'nullable|integer|min:0',
            'duration_unit' => 'required_with:duration|in:minutes,hours',
            'video_url' => 'nullable|url|max:255',
        ]);

        if ($request->parent_id == $id) {
            return back()->withErrors(['parent_id' => 'A session plan cannot be its own parent.']);
        }

        $data = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            if ($sessionPlan->attachment && file_exists(public_path($sessionPlan->attachment))) {
                @unlink(public_path($sessionPlan->attachment));
            }
            $file = $request->file('attachment');
            $fileName = 'session_plan_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/session_plans'), $fileName);
            $data['attachment'] = 'uploads/session_plans/' . $fileName;
        }

        $sessionPlan->update($data);

        return redirect()->route('teacher.session-plans.index')->with('success', 'Session Plan updated successfully.');
    }

    /**
     * Remove the resource.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $sessionPlan = SessionPlan::where('created_by', $user->id)->findOrFail($id);
        
        if ($sessionPlan->attachment && file_exists(public_path($sessionPlan->attachment))) {
            @unlink(public_path($sessionPlan->attachment));
        }
        
        $sessionPlan->delete();

        return redirect()->route('teacher.session-plans.index')->with('success', 'Session Plan deleted successfully.');
    }

    // AJAX Helpers
    public function getProgramsByCampus($campusId)
    {
        $user = Auth::user();
        $programIds = ClassSubject::where('teacher_id', $user->id)
            ->whereHas('class', function($q) use ($campusId) {
                $q->where('campus_id', $campusId);
            })
            ->with('subject.program')
            ->get()
            ->pluck('subject.program_id')
            ->unique()
            ->filter();

        $programs = Program::whereIn('id', $programIds)->get();

        $formattedPrograms = $programs->map(function ($program) {
            return [
                'id' => $program->id,
                'name' => method_exists($program, 'getTranslatedName') ? $program->getTranslatedName() : $program->name
            ];
        });

        return response()->json($formattedPrograms);
    }

    public function getSubjectsByProgram(Request $request, $programId)
    {
        $user = Auth::user();
        $subjectIds = ClassSubject::where('teacher_id', $user->id)
            ->whereHas('subject', function($q) use ($programId) {
                $q->where('program_id', $programId);
            })
            ->pluck('subject_id')
            ->unique();

        $subjects = Subject::whereIn('id', $subjectIds)->get();

        $formattedSubjects = $subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => (method_exists($subject, 'getTranslatedName') ? $subject->getTranslatedName() : $subject->name) . ' (' . $subject->code . ')'
            ];
        });

        return response()->json($formattedSubjects);
    }

    public function getSemestersByAcademicYear($yearId)
    {
        $semesters = Semester::where('academic_year_id', $yearId)->get();
        return response()->json($semesters);
    }

    public function getParentsBySubject($subjectId)
    {
        $parents = SessionPlan::where('subject_id', $subjectId)
            ->whereNull('parent_id')
            ->select('id', 'title')
            ->get();
        
        return response()->json($parents);
    }
}
