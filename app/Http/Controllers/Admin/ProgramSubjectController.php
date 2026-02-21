<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\ProgramSubject;

class ProgramSubjectController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::with('degreeLevel')->orderBy('name')->get();
        $academicYears = AcademicYear::with('semesters')->orderBy('name', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();

        $selectedProgramId = $request->get('program_id', $programs->first()?->id);
        $selectedYearId = $request->get('academic_year_id', $academicYears->first()?->id);

        $mappings = [];
        if ($selectedProgramId && $selectedYearId) {
            $mappings = ProgramSubject::with(['subject', 'semester'])
                ->where('program_id', $selectedProgramId)
                ->where('academic_year_id', $selectedYearId)
                ->get()
                ->groupBy(['level', 'semester_id']);
        }

        $semesters = [];
        if ($selectedYearId) {
            $semesters = Semester::where('academic_year_id', $selectedYearId)->get();
        }

        return view('admin.programs.subjects', compact(
            'programs', 
            'academicYears', 
            'subjects', 
            'mappings', 
            'selectedProgramId', 
            'selectedYearId',
            'semesters'
        ));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'level' => 'required|integer|min:1|max:10',
        ]);

        // Prevent duplicate assignment
        $exists = ProgramSubject::where([
            'program_id' => $request->program_id,
            'subject_id' => $request->subject_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'level' => $request->level,
        ])->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Subject already assigned to this semester.'], 422);
        }

        ProgramSubject::create($request->all());

        return response()->json(['success' => true, 'message' => 'Subject assigned successfully.']);
    }

    public function destroy($id)
    {
        $mapping = ProgramSubject::findOrFail($id);
        $mapping->delete();

        return response()->json(['success' => true, 'message' => 'Subject removed from curriculum.']);
    }
    public function getSubjects(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'campus_id' => 'required|exists:campuses,id',
            'program_id' => 'required|exists:programs,id',
        ]);

        $subjects = ProgramSubject::with('subject')
            ->where('semester_id', $request->semester_id)
            ->where('program_id', $request->program_id)
            ->get()
            ->map(function ($mapping) {
                return [
                    'id' => $mapping->subject->id,
                    'name' => $mapping->subject->getTranslatedName(),
                    'code' => $mapping->subject->code,
                ];
            });

        return response()->json($subjects);
    }
}
