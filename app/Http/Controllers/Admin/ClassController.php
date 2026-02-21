<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classes as ClassModel; // Use the correct model
use App\Models\ClassSubject;
use App\Models\Shift;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Program;
use App\Modules\Core\Models\Campus;
use App\Models\User;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $campuses = Campus::all();
        $years = AcademicYear::orderBy('name', 'desc')->get();
        $programs = \App\Modules\Academic\Models\Program::all();
        $shifts = Shift::all();
        
        $selectedCampusId = $request->get('campus_id', $campuses->first()?->id);
        $selectedYearId = $request->get('academic_year_id', $years->first()?->id);
        $selectedProgramId = $request->get('program_id');
        $selectedShiftId = $request->get('shift_id');
        
        $semesters = Semester::where('academic_year_id', $selectedYearId)->get();
        $selectedSemesterId = $request->get('semester_id', $semesters->first()?->id);

        $query = ClassModel::with(['classSubjects.subject', 'classSubjects.teacher', 'classSubjects.classroom', 'semester', 'campus', 'program', 'shift'])
            ->withCount('students');

        if ($selectedCampusId) $query->where('campus_id', $selectedCampusId);
        if ($selectedSemesterId) $query->where('semester_id', $selectedSemesterId);
        if ($selectedProgramId) $query->where('program_id', $selectedProgramId);
        if ($selectedShiftId) $query->where('shift_id', $selectedShiftId);

        // Filter classrooms by campus for the filter dropdown
        $classrooms = Classroom::where('campus_id', $selectedCampusId)->get();
        $selectedClassroomId = $request->get('classroom_id');
        if ($selectedClassroomId) {
            $query->whereHas('classSubjects', function($q) use ($selectedClassroomId) {
                $q->where('classroom_id', $selectedClassroomId);
            });
        }

        $classes = $query->latest()->get();

        return view('admin.classes.index', compact(
            'classes', 'campuses', 'years', 'semesters', 'classrooms', 'programs', 'shifts',
            'selectedCampusId', 'selectedYearId', 'selectedSemesterId', 'selectedClassroomId', 'selectedProgramId', 'selectedShiftId'
        ));
    }

    public function create()
    {
        $campuses = Campus::all();
        $semesters = Semester::with('academicYear')->get();
        $subjects = Subject::all();
        $teachers = User::where('role', 'teacher')->get();
        $classrooms = Classroom::all();
        $programs = Program::all();
        $shifts = Shift::where('is_active', true)->get();

        return view('admin.classes.create', compact('campuses', 'semesters', 'subjects', 'teachers', 'classrooms', 'programs', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'shift_id' => 'required|exists:shifts,id',
            'semester_id' => 'required|exists:semesters,id',
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:Scheduled,Ongoing,Completed,Cancelled',
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.teacher_id' => 'required|exists:users,id',
            'subjects.*.classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        // Generate a code if not provided (simple logic)
        $code = Str::upper(Str::slug($request->name)) . '-' . date('Y');
        // Ensure unique
        if(ClassModel::where('code', $code)->exists()){
             $code = $code . '-' . rand(100,999);
        }

        $class = ClassModel::create([
            'campus_id' => $request->campus_id,
            'shift_id' => $request->shift_id,
            'semester_id' => $request->semester_id,
            'program_id' => $request->program_id,
            'name' => $request->name,
            'code' => $code,
            'max_students' => $request->max_students,
            'status' => $request->status,
        ]);

        foreach ($request->subjects as $subj) {
            $schedule = null;
            
            // Handle Structured Schedule (Days + Time)
            if (isset($subj['days']) && is_array($subj['days'])) {
                $finalRanges = [];
                
                // Add preset session ranges
                if (!empty($subj['time_ranges']) && is_array($subj['time_ranges'])) {
                    foreach($subj['time_ranges'] as $tr) {
                        if($tr) $finalRanges[] = $tr;
                    }
                }

                // Add manual custom time
                if(!empty($subj['custom_start']) && !empty($subj['custom_end'])) {
                    $customRange = date('g:i A', strtotime($subj['custom_start'])) . ' - ' . date('g:i A', strtotime($subj['custom_end']));
                    $finalRanges[] = $customRange;
                }

                if(!empty($finalRanges)) {
                    $scheduleArray = [];
                    foreach($subj['days'] as $day) {
                        $scheduleArray[$day] = $finalRanges;
                    }
                    $schedule = $scheduleArray;
                }
            } 
            // Fallback to manual string/array input
            elseif (isset($subj['schedule'])) {
                $schedule = is_array($subj['schedule']) ? $subj['schedule'] : $subj['schedule'];
            }

            ClassSubject::create([
                'class_id' => $class->id,
                'subject_id' => $subj['subject_id'],
                'teacher_id' => $subj['teacher_id'],
                'classroom_id' => $subj['classroom_id'] ?? null,
                'schedule_json' => $schedule,
            ]);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class (Group) created successfully with ' . count($request->subjects) . ' subjects.');
    }

    public function edit($id)
    {
        $class = ClassModel::with('classSubjects')->findOrFail($id);
        $campuses = Campus::all();
        $semesters = Semester::with('academicYear')->get();
        $subjects = Subject::all();
        $teachers = User::where('role', 'teacher')->get();
        $programs = Program::all();
        // Get classrooms for the class campus
        $classrooms = Classroom::where('campus_id', $class->campus_id)->get();
        $shifts = Shift::where('is_active', true)->get();

        return view('admin.classes.edit', compact('class', 'campuses', 'semesters', 'subjects', 'teachers', 'classrooms', 'programs', 'shifts'));
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'shift_id' => 'required|exists:shifts,id',
            'semester_id' => 'required|exists:semesters,id',
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:Scheduled,Ongoing,Completed,Cancelled',
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.teacher_id' => 'required|exists:users,id',
            'subjects.*.classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        $class->update([
            'campus_id' => $request->campus_id,
            'shift_id' => $request->shift_id,
            'semester_id' => $request->semester_id,
            'program_id' => $request->program_id,
            'name' => $request->name,
            'max_students' => $request->max_students,
            'status' => $request->status,
        ]);

        // Sync Subjects: Delete all and recreate (Simplest for dynamic forms without ID tracking)
        // Alternatively, we could check IDs, but since it's just a mapping table, this is fine for now.
        $class->classSubjects()->delete();

        foreach ($request->subjects as $subj) {
            $schedule = null;
            
            // Handle Structured Schedule (Days + Time)
            if (isset($subj['days']) && is_array($subj['days'])) {
                $finalRanges = [];
                
                // Add preset session ranges
                if (!empty($subj['time_ranges']) && is_array($subj['time_ranges'])) {
                    foreach($subj['time_ranges'] as $tr) {
                        if($tr) $finalRanges[] = $tr;
                    }
                }

                // Add manual custom time
                if(!empty($subj['custom_start']) && !empty($subj['custom_end'])) {
                    $customRange = date('g:i A', strtotime($subj['custom_start'])) . ' - ' . date('g:i A', strtotime($subj['custom_end']));
                    $finalRanges[] = $customRange;
                }

                if(!empty($finalRanges)) {
                    $scheduleArray = [];
                    foreach($subj['days'] as $day) {
                        $scheduleArray[$day] = $finalRanges;
                    }
                    $schedule = $scheduleArray;
                }
            } 
            // Fallback to manual string/array input
            elseif (isset($subj['schedule'])) {
                 $schedule = is_array($subj['schedule']) ? $subj['schedule'] : $subj['schedule'];
            }

            ClassSubject::create([
                'class_id' => $class->id,
                'subject_id' => $subj['subject_id'],
                'teacher_id' => $subj['teacher_id'],
                'classroom_id' => $subj['classroom_id'] ?? null,
                'schedule_json' => $schedule,
            ]);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        
        if ($class->enrollments()->exists()) {
             return redirect()->back()->with('error', 'Cannot delete class with enrolled students.');
        }

        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }

    public function showQrCode($id)
    {
        // This could be an AJAX endpoint returning data for the modal
        // or a full page view. Based on index.blade, we use a modal there with external API.
        // This method is just a placeholder if we want to serve the QR code image ourselves later.
        // For now, it's not strictly used by the JS in index.blade.php as it uses 'showQrCode' JS function with static API.
        // But let's return the class info just in case we want to fetch details via AJAX.
        $class = ClassModel::findOrFail($id);
        return response()->json([
            'id' => $class->id,
            'name' => $class->name,
            'code' => $class->code,
            'enrollment_url' => url('/student/enroll/' . $class->code) // Fictional route for now
        ]);
    }
    public function printTimetable($id)
    {
        $class = ClassModel::with(['classSubjects.subject', 'classSubjects.teacher', 'classSubjects.classroom', 'semester', 'campus', 'program', 'shift'])->findOrFail($id);
        
        return view('admin.classes.timetable-print', compact('class'));
    }

    public function getShiftsByCampus($campusId)
    {
        $shifts = Shift::where('campus_id', $campusId)->where('is_active', true)->get();
        return response()->json($shifts);
    }
}
