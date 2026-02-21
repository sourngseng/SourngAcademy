<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Models\DegreeLevel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Academic\Models\ProgramImage;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Program::with(['department.faculty', 'degreeLevel'])->select('programs.*');

            // Filtering by Campus
            if ($request->filled('campus_id')) {
                $query->whereHas('department.faculty', function($q) use ($request) {
                    $q->where('campus_id', $request->campus_id);
                });
            }

            // Filtering by Level
            if ($request->filled('degree_level_id')) {
                $query->where('degree_level_id', $request->degree_level_id);
            }

            // Stats - Calculated based on filtered query
            $statsQuery = Program::query();
            
            if ($request->filled('campus_id')) {
                $statsQuery->whereHas('department.faculty', function($q) use ($request) {
                    $q->where('campus_id', $request->campus_id);
                });
            }

            if ($request->filled('degree_level_id')) {
                $statsQuery->where('degree_level_id', $request->degree_level_id);
            }

            $totalPrograms = (clone $statsQuery)->count();
            $popularPrograms = (clone $statsQuery)->where('is_popular', true)->count();
            $totalLevels = (clone $statsQuery)->distinct('degree_level_id')->count('degree_level_id');

            $stats = [
                'total' => $totalPrograms,
                'popular' => $popularPrograms,
                'total_levels' => $totalLevels,
            ];

            return DataTables::of($query)
                ->with('stats', $stats)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->feature_image) {
                        return '<a href="javascript:void(0)" class="view-program-details" data-id="'.$row->id.'"><img src="' . asset($row->feature_image) . '" class="img-thumbnail shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;"></a>';
                    }
                    return '<a href="javascript:void(0)" class="view-program-details" data-id="'.$row->id.'"><div class="text-center rounded-circle p-2 bg-light shadow-sm" style="width:50px; height:50px; display:inline-flex; align-items:center; justify-content:center;"><i class="fas fa-graduation-cap fa-lg text-primary"></i></div></a>';
                })
                ->addColumn('program_details', function ($row) {
                    $html = '<div class="font-weight-bold text-primary">' . $row->getTranslatedName() . '</div>';
                    if ($row->code) {
                        $html .= '<div class="small text-muted mt-1"><i class="fas fa-barcode mr-1"></i>' . $row->code . '</div>';
                    }
                    return $html;
                })
                ->addColumn('academic_info', function ($row) {
                    $degreeLabel = $row->degreeLevel ? $row->degreeLevel->name : '-';
                    $html = '<div class="badge badge-outline-primary border border-primary text-primary px-2 mb-1">' . $degreeLabel . '</div>';
                    $html .= '<div class="small text-dark mt-1"><i class="far fa-clock mr-1 text-info"></i>' . $row->duration_years . ' Year(s)</div>';
                    $html .= '<div class="small text-dark"><i class="fas fa-th-list mr-1 text-info"></i>' . $row->total_credits . ' Credits</div>';
                    return $html;
                })
                ->addColumn('department_name', function ($row) {
                    $name = $row->department ? (method_exists($row->department, 'getTranslatedName') ? $row->department->getTranslatedName() : $row->department->name) : '-';
                    return '<span class="text-secondary">' . $name . '</span>';
                })
                ->addColumn('popular', function ($row) {
                    $checked = $row->is_popular ? 'checked' : '';
                    return '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-popular" id="pop-'.$row->id.'" data-id="'.$row->id.'" '.$checked.'>
                                <label class="custom-control-label" for="pop-'.$row->id.'"></label>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.programs.edit', $row->id);
                    $duplicateUrl = route('admin.programs.duplicate', $row->id);
                    $deleteUrl = route('admin.programs.destroy', $row->id);
                    
                    $btn = '<div class="btn-group">';
                    $btn .= '<button type="button" class="btn btn-info btn-sm shadow-sm duplicate-btn" data-url="'.$duplicateUrl.'" title="Duplicate"><i class="fas fa-copy"></i></button>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-warning btn-sm shadow-sm mx-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="'.$row->id.'" data-url="'.$deleteUrl.'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    
                    return $btn;
                })
                ->rawColumns(['action', 'image', 'popular', 'program_details', 'academic_info', 'department_name'])
                ->make(true);
        }

        $campuses = \App\Modules\Core\Models\Campus::all();
        $degreeLevels = DegreeLevel::where('status', true)->orderBy('sort_order')->get();
        return view('admin.programs.index', compact('campuses', 'degreeLevels'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $program = Program::with(['department.faculty.campus', 'degreeLevel', 'images'])->findOrFail($id);
        
        return response()->json([
            'id' => $program->id,
            'name' => $program->getTranslatedName(),
            'code' => $program->code,
            'image' => $program->feature_image ? asset($program->feature_image) : null,
            'department' => $program->department ? (method_exists($program->department, 'getTranslatedName') ? $program->department->getTranslatedName() : $program->department->name) : 'N/A',
            'faculty' => ($program->department && $program->department->faculty) ? (method_exists($program->department->faculty, 'getTranslatedName') ? $program->department->faculty->getTranslatedName() : $program->department->faculty->name) : 'N/A',
            'campus' => ($program->department && $program->department->faculty && $program->department->faculty->campus) ? $program->department->faculty->campus->name : 'N/A',
            'level' => $program->degreeLevel ? $program->degreeLevel->name : 'N/A',
            'duration' => $program->duration_years . ' Year(s)',
            'credits' => $program->total_credits,
            'description' => $program->getTranslatedDescription(),
            'gallery' => $program->images->map(fn($img) => asset($img->image_path)),
            'is_popular' => $program->is_popular,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $degreeLevels = DegreeLevel::where('status', true)->orderBy('sort_order')->get();
        return view('admin.programs.create', compact('departments', 'degreeLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'code' => 'nullable|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'degree_level_id' => 'required|exists:degree_levels,id',
            'duration_years' => 'required|numeric|min:1|max:20',
            'total_credits' => 'required|numeric|min:1|max:10000',
            'feature_image' => 'nullable|image|max:2048',
            'is_popular' => 'nullable',
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

        $data['is_popular'] = $request->has('is_popular') ? 1 : 0;

        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            $imageName = 'program_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/programs'), $imageName);
            $data['feature_image'] = 'uploads/programs/' . $imageName;
        }

        $program = Program::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/programs/gallery'), $imageName);
                
                $program->images()->create([
                    'image_path' => 'uploads/programs/gallery/' . $imageName,
                    'order' => $index
                ]);
            }
        }

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $program = Program::with('images')->findOrFail($id);
        $departments = Department::all();
        $degreeLevels = DegreeLevel::where('status', true)->orderBy('sort_order')->get();
        return view('admin.programs.edit', compact('program', 'departments', 'degreeLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'code' => 'nullable|string|max:50|unique:programs,code,' . $id,
            'department_id' => 'required|exists:departments,id',
            'degree_level_id' => 'required|exists:degree_levels,id',
            'duration_years' => 'required|numeric|min:1|max:20',
            'total_credits' => 'required|numeric|min:1|max:10000',
            'feature_image' => 'nullable|image|max:2048',
            'is_popular' => 'nullable',
        ]);

        $program = Program::findOrFail($id);
        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $program->name;

        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                        ?? $request->input("translations.{$fallbackLocale}.description") 
                        ?? $program->description;

        $data['is_popular'] = $request->has('is_popular') ? 1 : 0;

        if ($request->hasFile('feature_image')) {
            // Delete old image if exists
            if ($program->feature_image && file_exists(public_path($program->feature_image))) {
                @unlink(public_path($program->feature_image));
            }
            
            $file = $request->file('feature_image');
            $imageName = 'program_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/programs'), $imageName);
            $data['feature_image'] = 'uploads/programs/' . $imageName;
        }

        $program->update($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            $lastOrder = $program->images()->max('order') ?? -1;
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/programs/gallery'), $imageName);
                
                $program->images()->create([
                    'image_path' => 'uploads/programs/gallery/' . $imageName,
                    'order' => $lastOrder + $index + 1
                ]);
            }
        }

        // Handle Image Deletion
        if ($request->has('delete_images')) {
            $imagesToDelete = ProgramImage::whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $img) {
                if ($img->image_path && file_exists(public_path($img->image_path))) {
                    @unlink(public_path($img->image_path));
                }
                $img->delete();
            }
        }

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $program = Program::findOrFail($id);

        if ($program->feature_image && file_exists(public_path($program->feature_image))) {
            @unlink(public_path($program->feature_image));
        }

        foreach ($program->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                @unlink(public_path($img->image_path));
            }
        }

        $program->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Program deleted successfully.']);
        }

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted successfully.');
    }

    /**
     * Toggle the popular status of a program.
     */
    public function togglePopular(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $program->is_popular = !$program->is_popular;
        $program->save();

        return response()->json([
            'success' => true,
            'message' => 'Program status updated successfully.',
            'is_popular' => $program->is_popular
        ]);
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate($id)
    {
        $program = Program::findOrFail($id);
        
        $newProgram = $program->replicate();
        
        // Update main name
        $newProgram->name = $newProgram->name . ' (Copy)';
        
        // Handle translations for name
        $translations = $newProgram->translations;
        if (is_array($translations)) {
            foreach ($translations as $key => $value) {
                if (isset($value['name'])) {
                    $translations[$key]['name'] = $value['name'] . ' (Copy)';
                }
            }
            $newProgram->translations = $translations;
        }

        // Handle Code (Truncate to ensure appending -COPY fits in 50 chars)
        if ($newProgram->code) {
             // Take first 44 chars to allow appending "-COPY" (5 chars) + maybe index?
             // Just -COPY is 5 chars. 50-5=45.
            $baseCode = substr($newProgram->code, 0, 44); 
            $newProgram->code = $baseCode . '-COPY';
            
            // Ensure unique if multiple copies
            $i = 1;
            while(Program::where('code', $newProgram->code)->exists()) {
                $newProgram->code = substr($baseCode, 0, 44 - strlen((string)$i)) . '-CP' . $i;
                $i++;
            }
        }
        
        $newProgram->is_popular = false;
        
        // Handle Feature Image duplication
        if ($program->feature_image && file_exists(public_path($program->feature_image))) {
            $extension = pathinfo($program->feature_image, PATHINFO_EXTENSION);
            $newImageName = 'program_' . time() . '_copy.' . $extension;
            copy(public_path($program->feature_image), public_path('uploads/programs/' . $newImageName));
            $newProgram->feature_image = 'uploads/programs/' . $newImageName;
        }

        $newProgram->save();

        // Duplicate Gallery Images
        foreach ($program->images as $image) {
            if (file_exists(public_path($image->image_path))) {
                $extension = pathinfo($image->image_path, PATHINFO_EXTENSION);
                $newImageName = 'gallery_' . time() . '_' . $image->order . '_copy.' . $extension;
                copy(public_path($image->image_path), public_path('uploads/programs/gallery/' . $newImageName));
                
                $newProgram->images()->create([
                    'image_path' => 'uploads/programs/gallery/' . $newImageName,
                    'order' => $image->order
                ]);
            }
        }

        return redirect()->route('admin.programs.edit', $newProgram->id)->with('success', 'Program duplicated successfully. You are now editing the copy.');
    }
}
