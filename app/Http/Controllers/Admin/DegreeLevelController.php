<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\DegreeLevel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DegreeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DegreeLevel::orderBy('sort_order')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status 
                        ? '<span class="badge badge-success">Active</span>' 
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.degree-levels.edit', $row->id);
                    $deleteUrl = route('admin.degree-levels.destroy', $row->id);
                    $btn = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm mr-1 shadow-sm"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="'.$row->id.'" data-url="'.$deleteUrl.'"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.degree_levels.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.degree_levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        DegreeLevel::create($data);

        return redirect()->route('admin.degree-levels.index')->with('success', 'Degree Level created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $degreeLevel = DegreeLevel::findOrFail($id);
        return view('admin.degree_levels.edit', compact('degreeLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        $degreeLevel = DegreeLevel::findOrFail($id);
        $data = $request->all();
        $data['status'] = $request->has('status');

        $degreeLevel->update($data);

        return redirect()->route('admin.degree-levels.index')->with('success', 'Degree Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $degreeLevel = DegreeLevel::findOrFail($id);
        $degreeLevel->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Degree Level deleted successfully.']);
        }

        return redirect()->route('admin.degree-levels.index')->with('success', 'Degree Level deleted successfully.');
    }
}
