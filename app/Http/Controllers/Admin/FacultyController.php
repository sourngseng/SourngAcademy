<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Faculty;
use App\Modules\Core\Models\Campus;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Faculty::with('campus')->select('faculties.*');
            
            if ($request->filled('campus_id')) {
                $query->where('campus_id', $request->campus_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('campus_name', function ($row) {
                    return $row->campus ? $row->campus->getTranslatedName() : '-';
                })
                ->addColumn('name', function ($row) {
                    return $row->getTranslatedName();
                })
                ->addColumn('image', function ($row) {
                    if ($row->feature_image) {
                        return '<img src="' . asset($row->feature_image) . '" class="img-thumbnail shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;">';
                    } elseif ($row->icon) {
                        return '<div class="text-center"><i class="' . $row->icon . ' fa-2x text-primary shadow-sm rounded-circle p-2 bg-light"></i></div>';
                    }
                    return '<div class="text-center"><i class="fas fa-university fa-2x text-primary shadow-sm rounded-circle p-2 bg-light"></i></div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.faculties.edit', $row->id);
                    $duplicateUrl = route('admin.faculties.duplicate', $row->id);
                    $deleteUrl = route('admin.faculties.destroy', $row->id);

                    $btn = '<div class="btn-group">';
                    $btn .= '<button type="button" class="btn btn-info btn-sm shadow-sm duplicate-btn" data-url="'.$duplicateUrl.'" title="Duplicate"><i class="fas fa-copy"></i></button>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-warning btn-sm shadow-sm mx-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="'.$row->id.'" data-url="'.$deleteUrl.'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        $campuses = Campus::where('is_active', true)->get();
        return view('admin.faculties.index', compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campuses = Campus::where('is_active', true)->get();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.faculties.create', compact('campuses', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'code' => 'nullable|string|max:50',
            'campus_id' => 'required|exists:campuses,id',
            'dean_name' => 'nullable|string|max:255',
            'feature_image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $request->input('name') 
                        ?? '';
        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                        ?? $request->input("translations.{$fallbackLocale}.description") 
                        ?? $request->input('description') 
                        ?? '';

        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        
        // Ensure unique slug
        if (Faculty::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $data['slug'] . '-' . time();
        }

        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            $imageName = 'faculty_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/faculties'), $imageName);
            $data['feature_image'] = 'uploads/faculties/' . $imageName;
        }

        Faculty::create($data);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $faculty = Faculty::findOrFail($id);
        $campuses = Campus::where('is_active', true)->get();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.faculties.edit', compact('faculty', 'campuses', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'slug' => 'nullable|string|max:255|unique:faculties,slug,' . $id,
            'code' => 'nullable|string|max:50',
            'campus_id' => 'required|exists:campuses,id',
            'dean_name' => 'nullable|string|max:255',
            'feature_image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
        ]);

        $faculty = Faculty::findOrFail($id);
        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $faculty->name;

        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                        ?? $request->input("translations.{$fallbackLocale}.description") 
                        ?? $faculty->description;

        if (!$request->filled('slug')) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            // Ensure unique slug
            if (Faculty::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }
        }

        if ($request->hasFile('feature_image')) {
            // Delete old image if exists
            if ($faculty->feature_image && file_exists(public_path($faculty->feature_image))) {
                @unlink(public_path($faculty->feature_image));
            }

            $file = $request->file('feature_image');
            $imageName = 'faculty_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/faculties'), $imageName);
            $data['feature_image'] = 'uploads/faculties/' . $imageName;
        }

        $faculty->update($data);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faculty = Faculty::findOrFail($id);
        
        if ($faculty->feature_image && file_exists(public_path($faculty->feature_image))) {
            @unlink(public_path($faculty->feature_image));
        }

        $faculty->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Faculty deleted successfully.']);
        }

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty deleted successfully.');
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate($id)
    {
        $faculty = Faculty::findOrFail($id);
        
        $newFaculty = $faculty->replicate();
        $newFaculty->name = $newFaculty->name . ' (Copy)';
        
        // Handle translations for name
        $translations = $newFaculty->translations;
        if (is_array($translations)) {
            foreach ($translations as $key => $value) {
                if (isset($value['name'])) {
                    $translations[$key]['name'] = $value['name'] . ' (Copy)';
                }
            }
            $newFaculty->translations = $translations;
        }

        // Handle Slug
        $newFaculty->slug = $newFaculty->slug . '-copy-' . time();

        // Handle Code
        if ($newFaculty->code) {
             $baseCode = substr($newFaculty->code, 0, 44); 
             $newFaculty->code = $baseCode . '-COPY';
             
             $i = 1;
             while(Faculty::where('code', $newFaculty->code)->exists()) {
                 $newFaculty->code = substr($baseCode, 0, 44 - strlen((string)$i)) . '-CP' . $i;
                 $i++;
             }
        }

        // Handle Feature Image duplication
        if ($faculty->feature_image && file_exists(public_path($faculty->feature_image))) {
            $extension = pathinfo($faculty->feature_image, PATHINFO_EXTENSION);
            $newImageName = 'faculty_' . time() . '_copy.' . $extension;
            copy(public_path($faculty->feature_image), public_path('uploads/faculties/' . $newImageName));
            $newFaculty->feature_image = 'uploads/faculties/' . $newImageName;
        }

        $newFaculty->save();

        return redirect()->route('admin.faculties.edit', $newFaculty->id)->with('success', 'Faculty duplicated successfully. You are now editing the copy.');
    }
}
