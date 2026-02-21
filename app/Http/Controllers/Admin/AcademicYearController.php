<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Modules\Core\Models\Campus;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::with('campus', 'semesters')->latest()->get();
        return view('admin.academic_years.index', compact('years'));
    }

    public function create()
    {
        $campuses = Campus::all();
        return view('admin.academic_years.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $year = AcademicYear::create($request->all());

        // Automatically create 2 semesters per year
        $midDate = \Carbon\Carbon::parse($request->start_date)->addMonths(5);
        $sem2StartDate = \Carbon\Carbon::parse($midDate)->addDay();
        
        Semester::create([
            'academic_year_id' => $year->id,
            'name' => 'Semester 1',
            'start_date' => $request->start_date,
            'end_date' => $midDate->toDateString(),
            'is_active' => true
        ]);

        Semester::create([
            'academic_year_id' => $year->id,
            'name' => 'Semester 2',
            'start_date' => $sem2StartDate->toDateString(),
            'end_date' => $request->end_date,
            'is_active' => false
        ]);

        return redirect()->route('admin.academic-years.index')->with('success', 'Academic Year created with 2 semesters.');
    }

    public function edit($id)
    {
        $year = AcademicYear::with('semesters')->findOrFail($id);
        $campuses = Campus::all();
        return view('admin.academic_years.edit', compact('year', 'campuses'));
    }

    public function update(Request $request, $id)
    {
        $year = AcademicYear::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $year->update($request->all());
        return redirect()->route('admin.academic-years.index')->with('success', 'Academic Year updated successfully.');
    }

    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->delete();
        return redirect()->route('admin.academic-years.index')->with('success', 'Academic Year deleted successfully.');
    }
}
