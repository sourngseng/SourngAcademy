<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\SessionPlan;
use App\Modules\Core\Models\Campus;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\ProgramSubject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SessionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SessionPlan::with(['campus', 'program', 'subject', 'academicYear', 'semester', 'author', 'children'])
            ->withCount('children')
            ->whereNull('parent_id');

        if ($request->filled('campus_id')) $query->where('campus_id', $request->campus_id);
        if ($request->filled('program_id')) $query->where('program_id', $request->program_id);
        if ($request->filled('subject_id')) $query->where('subject_id', $request->subject_id);
        if ($request->filled('academic_year_id')) $query->where('academic_year_id', $request->academic_year_id);
        if ($request->filled('semester_id')) $query->where('semester_id', $request->semester_id);
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $campuses = Campus::all();
        $academicYears = AcademicYear::all();

        if ($request->ajax()) {
            return view('admin.session-plans.partials._list', compact('sessions'))->render();
        }

        return view('admin.session-plans.index', compact('campuses', 'academicYears', 'sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campuses = Campus::all();
        $academicYears = AcademicYear::where('status', 1)->get();
        // Semesters depends on academic year, usually loaded via AJAX
        return view('admin.session-plans.create', compact('campuses', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'program_id' => 'required|exists:programs,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:session_plans,id',
            'duration' => 'nullable|integer|min:0',
            'duration_unit' => 'required_with:duration|in:minutes,hours',
            'video_url' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'session_plan_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/session_plans'), $fileName);
            $data['attachment'] = 'uploads/session_plans/' . $fileName;
        }

        SessionPlan::create($data);

        return redirect()->route('admin.session-plans.index')->with('success', 'Session Plan created successfully.');
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate($id)
    {
        $sessionPlan = SessionPlan::findOrFail($id);

        $newPlan = $sessionPlan->replicate();
        $newPlan->title = $sessionPlan->title . ' (Copy)';
        $newPlan->created_by = Auth::id();
        $newPlan->created_at = now();
        $newPlan->updated_at = now();

        // Handle File Copy
        if ($sessionPlan->attachment && file_exists(public_path($sessionPlan->attachment))) {
            $info = pathinfo($sessionPlan->attachment);
            // $info['dirname'] is like 'uploads/session_plans' (relative) or absolute? 
            // sessionPlan->attachment stored as 'uploads/session_plans/filename.ext'
            
            $ext = $info['extension'];
            $filename = $info['filename'];
            $newFileName = $filename . '_copy_' . time() . '.' . $ext;
            $newPath = 'uploads/session_plans/' . $newFileName;
            
            try {
                copy(public_path($sessionPlan->attachment), public_path($newPath));
                $newPlan->attachment = $newPath;
            } catch (\Exception $e) {
                // Log error or just ignore file copy failure, leaving attachment null is safer than crashing
                $newPlan->attachment = null;
            }
        }

        $newPlan->save();

        if (request()->ajax()) {
            return response()->json(['success' => 'Session Plan duplicated successfully.']);
        }

        return redirect()->route('admin.session-plans.index')->with('success', 'Session Plan duplicated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sessionPlan = SessionPlan::with(['campus', 'program', 'subject', 'academicYear', 'semester', 'author', 'parent', 'children'])
            ->findOrFail($id);
            
        // Fetch all top-level sessions for the same subject context to build the sidebar roadmap
        $roadmapSessions = SessionPlan::where('subject_id', $sessionPlan->subject_id)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('admin.session-plans.show', compact('sessionPlan', 'roadmapSessions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sessionPlan = SessionPlan::findOrFail($id);
        $campuses = Campus::all();
        $academicYears = AcademicYear::all();
        
        // Load dependent data
        $programs = Program::whereHas('department.faculty', function($q) use($sessionPlan) {
            $q->where('campus_id', $sessionPlan->campus_id);
        })->get();
        
        $subjects = $sessionPlan->program->subjects;
        
        $semesters = [];
        if($sessionPlan->academic_year_id) {
            $semesters = Semester::where('academic_year_id', $sessionPlan->academic_year_id)->get();
        }

        // Potential Parents: Same subject, not self, not a child of self (simple check: not self)
        // Ideally should check for circular dependency, but for single level or simple trees, just excluding self is basic.
        $parents = SessionPlan::where('subject_id', $sessionPlan->subject_id)
            ->where('id', '!=', $sessionPlan->id)
            ->whereNull('parent_id') // Optional: Limit hierarchy depth to 2 levels (Parent -> Child) if desired. Or allow deep nesting. Let's allow deep for now but filter reasonable parents.
            ->get();
        
        return view('admin.session-plans.edit', compact('sessionPlan', 'campuses', 'academicYears', 'programs', 'subjects', 'semesters', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
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

        $sessionPlan = SessionPlan::findOrFail($id);
        
        // Prevent setting parent to itself
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

        return redirect()->route('admin.session-plans.index')->with('success', 'Session Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sessionPlan = SessionPlan::findOrFail($id);
        
        if ($sessionPlan->attachment && file_exists(public_path($sessionPlan->attachment))) {
            @unlink(public_path($sessionPlan->attachment));
        }
        
        $sessionPlan->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Session Plan deleted successfully.']);
        }

        return redirect()->route('admin.session-plans.index')->with('success', 'Session Plan deleted successfully.');
    }

    // API Helpers
    public function getProgramsByCampus($campusId)
    {
        // Programs related to Campus via Department -> Faculty -> Campus
        $programs = Program::whereHas('department.faculty', function ($q) use ($campusId) {
            $q->where('campus_id', $campusId);
        })->get();

        $formattedPrograms = $programs->map(function ($program) {
            return [
                'id' => $program->id,
                'name' => method_exists($program, 'getTranslatedName') ? $program->getTranslatedName() : $program->name
            ];
        });

        return response()->json($formattedPrograms);
    }

    public function getSemestersByAcademicYear($yearId)
    {
        $semesters = Semester::where('academic_year_id', $yearId)->get();
        return response()->json($semesters);
    }

    public function getAcademicYearsByCampus($campusId)
    {
        $years = AcademicYear::where('campus_id', $campusId)->get();
        return response()->json($years);
    }

    public function getSubjectsByProgram(Request $request, $programId)
    {
        $query = ProgramSubject::where('program_id', $programId)
            ->with('subject');
        
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        $programSubjects = $query->get();
        $formattedSubjects = $programSubjects->map(function ($ps) {
            $subject = $ps->subject;
            if (!$subject) return null;
            return [
                'id' => $subject->id,
                'name' => (method_exists($subject, 'getTranslatedName') ? $subject->getTranslatedName() : $subject->name) . ' (' . $subject->code . ')'
            ];
        })->filter()->values();

        return response()->json($formattedSubjects);
    }

    public function getParentsBySubject($subjectId)
    {
        // Only fetch top-level plans (parent_id is null) to act as parents
        $parents = SessionPlan::where('subject_id', $subjectId)
            ->whereNull('parent_id')
            ->select('id', 'title')
            ->get();
        
        return response()->json($parents);
    }
}
