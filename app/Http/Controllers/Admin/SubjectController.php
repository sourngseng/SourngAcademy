<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\Program;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Academic\Models\SubjectImage;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Subject::with(['program.department.faculty'])->select('subjects.*');

            // Filtering by Campus
            if ($request->filled('campus_id')) {
                $query->whereHas('program.department.faculty', function($q) use ($request) {
                    $q->where('campus_id', $request->campus_id);
                });
            }

            // Filtering by Program
            if ($request->filled('program_id')) {
                $query->where('program_id', $request->program_id);
            }

            // Stats based on filtered query
            $statsQuery = Subject::query();
            if ($request->filled('campus_id')) {
                $statsQuery->whereHas('program.department.faculty', function($q) use ($request) {
                    $q->where('campus_id', $request->campus_id);
                });
            }
            if ($request->filled('program_id')) {
                $statsQuery->where('program_id', $request->program_id);
            }

            $totalSubjects = (clone $statsQuery)->count();
            $totalCredits = (clone $statsQuery)->sum('credit');
            $coreSubjects = (clone $statsQuery)->where('type', 'Core')->count();

            $stats = [
                'total' => $totalSubjects,
                'credits' => $totalCredits,
                'core' => $coreSubjects,
            ];

            return DataTables::of($query)
                ->with('stats', $stats)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->feature_image) {
                        return '<a href="javascript:void(0)" class="view-subject-details" data-id="'.$row->id.'"><img src="' . asset($row->feature_image) . '" class="img-thumbnail shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border-radius: 8px;"></a>';
                    }
                    return '<a href="javascript:void(0)" class="view-subject-details" data-id="'.$row->id.'"><div class="text-center rounded-circle p-2 bg-light shadow-sm" style="width:50px; height:50px; display:inline-flex; align-items:center; justify-content:center;"><i class="fas fa-book fa-lg text-primary"></i></div></a>';
                })
                ->addColumn('subject_details', function ($row) {
                    $html = '<div class="font-weight-bold text-primary">' . $row->getTranslatedName() . '</div>';
                    if ($row->code) {
                        $html .= '<div class="small text-muted mt-1"><i class="fas fa-barcode mr-1"></i>' . $row->code . '</div>';
                    }
                    return $html;
                })
                ->addColumn('academic_info', function ($row) {
                    $typeClass = $row->type == 'Core' ? 'badge-primary' : ($row->type == 'Elective' ? 'badge-success' : 'badge-info');
                    $html = '<div><span class="badge ' . $typeClass . ' px-2 py-1">' . $row->type . '</span></div>';
                    $html .= '<div class="small mt-1"><i class="fas fa-coins text-warning mr-1"></i>' . $row->credit . ' Credits</div>';
                    return $html;
                })
                ->addColumn('program_name', function ($row) {
                    return $row->program ? $row->program->getTranslatedName() : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.subjects.edit', $row->id);
                    $deleteUrl = route('admin.subjects.destroy', $row->id);
                    $btn = '<div class="btn-group">';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-info btn-sm view-subject-details" data-id="'.$row->id.'" title="View Details"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm mx-1 shadow-sm" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="'.$row->id.'" data-url="'.$deleteUrl.'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'image', 'subject_details', 'academic_info', 'program_name'])
                ->make(true);
        }

        $campuses = \App\Modules\Core\Models\Campus::all();
        $programs = Program::all();
        return view('admin.subjects.index', compact('campuses', 'programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::all();
        return view('admin.subjects.create', compact('programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'program_id' => 'required|exists:programs,id',
            'credit' => 'required|numeric|min:0|max:20',
            'type' => 'required|string',
            'feature_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Fallback for main columns
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

        if ($request->hasFile('feature_image')) {
            $imageName = 'subject_'.time().'.'.$request->feature_image->extension();
            $request->feature_image->move(public_path('uploads/subjects'), $imageName);
            $data['feature_image'] = 'uploads/subjects/'.$imageName;
        }

        $subject = Subject::create($data);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_'.time().'_'.$index.'.'.$image->extension();
                $image->move(public_path('uploads/subjects/gallery'), $imageName);
                
                $subject->images()->create([
                    'image_path' => 'uploads/subjects/gallery/'.$imageName,
                    'order' => $index
                ]);
            }
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subject = Subject::with('images')->findOrFail($id);
        $programs = Program::all();
        return view('admin.subjects.edit', compact('subject', 'programs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'translations.*.name' => 'required_with:translations|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'program_id' => 'required|exists:programs,id',
            'credit' => 'required|numeric|min:0|max:20',
            'type' => 'required|string',
            'feature_image' => 'nullable|image|max:2048',
        ]);

        $subject = Subject::findOrFail($id);
        $data = $request->except('feature_image', 'translations');
        $data['translations'] = $request->input('translations', []);

        // Fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $subject->name;
        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                        ?? $request->input("translations.{$fallbackLocale}.description") 
                        ?? $subject->description;

        if ($request->hasFile('feature_image')) {
            if ($subject->feature_image && file_exists(public_path($subject->feature_image))) {
                @unlink(public_path($subject->feature_image));
            }
            $imageName = 'subject_'.time().'.'.$request->feature_image->extension();
            $request->feature_image->move(public_path('uploads/subjects'), $imageName);
            $data['feature_image'] = 'uploads/subjects/'.$imageName;
        }

        $subject->update($data);

        if ($request->hasFile('gallery_images')) {
            $lastOrder = $subject->images()->max('order') ?? -1;
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_'.time().'_'.$index.'.'.$image->extension();
                $image->move(public_path('uploads/subjects/gallery'), $imageName);
                
                $subject->images()->create([
                    'image_path' => 'uploads/subjects/gallery/'.$imageName,
                    'order' => $lastOrder + $index + 1
                ]);
            }
        }

        if ($request->has('delete_images')) {
            $imagesToDelete = SubjectImage::whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $img) {
                if (file_exists(public_path($img->image_path))) {
                    @unlink(public_path($img->image_path));
                }
                $img->delete();
            }
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        if ($subject->feature_image && file_exists(public_path($subject->feature_image))) {
            @unlink(public_path($subject->feature_image));
        }

        foreach ($subject->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                @unlink(public_path($img->image_path));
            }
        }

        $subject->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Subject deleted successfully.']);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }

    public function show($id)
    {
        $subject = Subject::with(['program.department.faculty.campus', 'images'])->findOrFail($id);
        
        return response()->json([
            'id' => $subject->id,
            'name' => $subject->getTranslatedName(),
            'code' => $subject->code,
            'image' => $subject->feature_image ? asset($subject->feature_image) : null,
            'program' => $subject->program ? $subject->program->getTranslatedName() : 'N/A',
            'campus' => ($subject->program && $subject->program->department && $subject->program->department->faculty && $subject->program->department->faculty->campus) ? $subject->program->department->faculty->campus->getTranslatedName() : 'N/A',
            'credit' => $subject->credit . ' Credits',
            'type' => $subject->type,
            'description' => $subject->getTranslatedDescription(),
            'gallery' => $subject->images->map(fn($img) => asset($img->image_path)),
        ]);
    }
}
