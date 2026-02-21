<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\AcademicYear;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademicYear::orderBy('name', 'desc')->get();
        $selectedYearId = $request->get('academic_year_id', $years->first()?->id);

        $query = Semester::with('academicYear');
        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }

        $semesters = $query->orderBy('start_date')->get();

        return view('admin.semesters.index', compact('semesters', 'years', 'selectedYearId'));
    }

    public function create()
    {
        $years = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.semesters.create', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Semester::create($request->all());
        return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully.');
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        $years = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.semesters.edit', compact('semester', 'years'));
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $semester->update($request->all());
        return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        
        // Safety check: Prevent deletion if classes exist
        if ($semester->classes()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete semester because it has active classes.');
        }

        $semester->delete();
        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully.');
    }
}
