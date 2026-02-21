<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Models\Faculty;
use App\Modules\Core\Models\Campus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Department::with('faculty', 'headOfDepartment')->select('departments.*');

            if ($request->filled('campus_id')) {
                $query->whereHas('faculty', function($q) use ($request) {
                    $q->where('campus_id', $request->campus_id);
                });
            }

            // Global Stats (fast)
            // When filtering, stats should arguably reflect the filter, but let's keep them global as requested "stats" usually implies total overview
            $totalDepartments = Department::count();
            $departmentsWithHod = Department::whereNotNull('head_of_department_id')->count();
            $totalFaculties = Faculty::count();

            $stats = [
                'total' => $totalDepartments,
                'with_hod' => $departmentsWithHod,
                'total_faculties' => $totalFaculties
            ];

            return DataTables::of($query)
                ->with('stats', $stats)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->getTranslatedName();
                })
                ->addColumn('faculty_name', function ($row) {
                    return $row->faculty ? (method_exists($row->faculty, 'getTranslatedName') ? $row->faculty->getTranslatedName() : $row->faculty->name) : '-';
                })
                ->addColumn('hod_name', function ($row) {
                    return $row->headOfDepartment ? $row->headOfDepartment->name : '-';
                })
                ->editColumn('feature_image', function($row) {
                    if($row->feature_image){
                        return '<a href="javascript:void(0)" class="view-department-details" data-id="'.$row->id.'"><img src="'.asset($row->feature_image).'" alt="Department Image" class="img-thumbnail shadow-sm" style="height: 50px; width: 50px; object-fit: cover;"></a>';
                    }
                    return '<a href="javascript:void(0)" class="view-department-details" data-id="'.$row->id.'"><div class="bg-light d-flex align-items-center justify-content-center border rounded shadow-sm" style="height: 50px; width: 50px;"><i class="fas fa-image text-muted"></i></div></a>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.departments.edit', $row->id);
                    $deleteUrl = route('admin.departments.destroy', $row->id);
                    $btn = '<a href="' . $editUrl . '" class="btn btn-warning btn-sm mr-1 shadow-sm"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="'.$row->id.'" data-url="'.$deleteUrl.'"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['feature_image', 'action', 'code']) // added feature_image to raw columns
                ->make(true);
        }
        
        $campuses = Campus::all();
        return view('admin.departments.index', compact('campuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::with('faculty', 'headOfDepartment')->findOrFail($id);
        
        return response()->json([
            'id' => $department->id,
            'name' => $department->getTranslatedName(),
            'code' => $department->code,
            'image' => $department->feature_image ? asset($department->feature_image) : null,
            'faculty' => $department->faculty ? (method_exists($department->faculty, 'getTranslatedName') ? $department->faculty->getTranslatedName() : $department->faculty->name) : 'N/A',
            'hod' => $department->headOfDepartment ? $department->headOfDepartment->name : 'N/A',
            'email' => $department->email,
            'phone' => $department->phone,
            'working_hours' => $department->working_hours,
            'description' => $department->getTranslatedDescription(),
            'mission' => $department->getTranslatedMission(),
            'vision' => $department->getTranslatedVision(),
            'goal' => $department->getTranslatedGoal(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $faculties = Faculty::all();
        $users = User::all(); // In a real app, filter this by role
        return view('admin.departments.create', compact('faculties', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.mission' => 'nullable|string',
            'translations.*.vision' => 'nullable|string',
            'translations.*.goal' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:departments,slug',
            'code' => 'nullable|string|max:50',
            'faculty_id' => 'required|exists:faculties,id',
            'head_of_department_id' => 'nullable|exists:users,id',
            'feature_image' => 'nullable|image|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'working_hours' => 'nullable|string|max:255',
        ]);

        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? '';
                        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                               ?? $request->input("translations.{$fallbackLocale}.description") 
                               ?? '';

        $data['mission'] = $request->input("translations.{$defaultLocale}.mission")
                           ?? $request->input("translations.{$fallbackLocale}.mission")
                           ?? '';

        $data['vision'] = $request->input("translations.{$defaultLocale}.vision")
                           ?? $request->input("translations.{$fallbackLocale}.vision")
                           ?? '';

        $data['goal'] = $request->input("translations.{$defaultLocale}.goal")
                           ?? $request->input("translations.{$fallbackLocale}.goal")
                           ?? '';

        
        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            // If slug is empty (e.g. non-latin characters and slugify issues), generate a random one or use ID later
            if(empty($data['slug'])) $data['slug'] = 'dept-' . time();

            // Ensure uniqueness
            $count = 1;
            $originalSlug = $data['slug'];
            while (Department::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle Image
        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            $imageName = 'dept_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/departments'), $imageName);
            $data['feature_image'] = 'uploads/departments/' . $imageName;
        }

        Department::create($data);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $faculties = Faculty::all();
        $users = User::all();
        return view('admin.departments.edit', compact('department', 'faculties', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.mission' => 'nullable|string',
            'translations.*.vision' => 'nullable|string',
            'translations.*.goal' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:departments,slug,' . $id,
            'code' => 'nullable|string|max:50',
            'faculty_id' => 'required|exists:faculties,id',
            'head_of_department_id' => 'nullable|exists:users,id',
            'feature_image' => 'nullable|image|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'working_hours' => 'nullable|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $department->name;
                        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                               ?? $request->input("translations.{$fallbackLocale}.description") 
                               ?? $department->description;

        $data['mission'] = $request->input("translations.{$defaultLocale}.mission")
                           ?? $request->input("translations.{$fallbackLocale}.mission")
                           ?? $department->mission;

        $data['vision'] = $request->input("translations.{$defaultLocale}.vision")
                           ?? $request->input("translations.{$fallbackLocale}.vision")
                           ?? $department->vision;

        $data['goal'] = $request->input("translations.{$defaultLocale}.goal")
                           ?? $request->input("translations.{$fallbackLocale}.goal")
                           ?? $department->goal;

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            if(empty($data['slug'])) $data['slug'] = 'dept-' . time();
            
            // Ensure uniqueness
            $count = 1;
            $originalSlug = $data['slug'];
            while (Department::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle Image
        if ($request->hasFile('feature_image')) {
            // Delete old image if exists
            if ($department->feature_image && file_exists(public_path($department->feature_image))) {
                @unlink(public_path($department->feature_image));
            }

            $file = $request->file('feature_image');
            $imageName = 'dept_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/departments'), $imageName);
            $data['feature_image'] = 'uploads/departments/' . $imageName;
        }

        $department->update($data);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Department deleted successfully.']);
        }

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
