<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Modules\Core\Models\Campus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Classroom::with('campus')->latest();

            // Filters
            if ($request->filled('campus_id')) {
                $query->where('campus_id', $request->campus_id);
            }
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('building')) {
                $query->where('building', 'like', '%' . $request->building . '%');
            }

            // Calculate stats based on the filtered query
            // Clone the query before DataTables modifies it
            $statsQuery = clone $query;
            $total = $statsQuery->count();
            $capacity = $statsQuery->sum('capacity');
            
            // For sub-stats, we need separate clones to avoid conflicting constraints if filters are overly specific,
            // but generally we want "Out of the current filtered results, how many are Labs/Active?"
            
            $activeQuery = clone $query;
            // If the status filter is already '0' (Inactive), then count of 'Active' (1) should be 0.
            // Use where logic carefully. 
            // If $query already has `where status = 0`, adding `where status = 1` makes it impossible (count 0).
            // This is correct behavior for "Active Rooms in current list".
            $activeCount = $activeQuery->where('status', true)->count();
            
            $labsQuery = clone $query;
            $labsCount = $labsQuery->where('type', 'Lab')->count();
            
            $stats = [
                'total' => $total,
                'total_capacity' => $capacity,
                'active' => $activeCount,
                'labs' => $labsCount,
            ];

            return DataTables::of($query)
                ->with('stats', $stats)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    // Combine Room Number and Name for better context
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="font-weight-bold text-primary">' . $row->room_number . '</span>';
                    $html .= '<small class="text-muted">' . $row->getTranslatedName() . '</small>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('details', function($row){
                     return \Illuminate\Support\Str::limit($row->getTranslatedDescription(), 50) ?: '<span class="text-muted font-italic">No description</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group btn-group-sm">';
                    $btn .= '<a href="'.route('admin.classrooms.edit', $row->id).'" class="btn btn-info" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-danger delete-btn" data-id="'.$row->id.'" data-url="'.route('admin.classrooms.destroy', $row->id).'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('image', function($row) {
                    if($row->image){
                        return '<a href="javascript:void(0)" class="view-classroom-details" data-id="'.$row->id.'"><img src="'.asset($row->image).'" alt="Room Image" class="img-thumbnail" style="height: 50px; width: 80px; object-fit: cover;"></a>';
                    }
                    return '<a href="javascript:void(0)" class="view-classroom-details" data-id="'.$row->id.'"><div class="bg-light d-flex align-items-center justify-content-center border rounded" style="height: 50px; width: 80px;"><i class="fas fa-image text-muted"></i></div></a>';
                })
                ->editColumn('status', function($row){
                    if($row->status) {
                        return '<span class="badge badge-success px-2 py-1">Active</span>';
                    } else {
                        return '<span class="badge badge-secondary px-2 py-1">Inactive</span>';
                    }
                })
                ->editColumn('type', function($row){
                    $badges = [
                        'Classroom' => 'primary',
                        'Lecture Hall' => 'info',
                        'Lab' => 'warning',
                        'Online' => 'purple',
                    ];
                    $color = $badges[$row->type] ?? 'secondary';
                    // custom purple class needed or use simple indigo
                    if($color == 'purple') $color = 'indigo'; 
                    return '<span class="badge bg-'.$color.'">'.$row->type.'</span>';
                })
                ->addColumn('location', function($row){
                    $html = '<div><i class="fas fa-building text-muted mr-1"></i> ' . ($row->building ?? 'N/A') . '</div>';
                    $html .= '<small><i class="fas fa-layer-group text-muted mr-1"></i> ' . ($row->floor ?? 'N/A') . '</small>';
                    return $html;
                })
                ->editColumn('capacity', function($row){
                    return $row->capacity ? '<i class="fas fa-chair"></i> ' . $row->capacity : 'N/A';
                })
                ->rawColumns(['action', 'image', 'status', 'name', 'details', 'location', 'capacity', 'type'])
                ->make(true);
        }

        // Stats for cards
        $stats = [
            'total' => Classroom::count(),
            'total_capacity' => Classroom::sum('capacity'),
            'active' => Classroom::where('status', true)->count(),
            'labs' => Classroom::where('type', 'Lab')->count(),
        ];
        
        $campuses = Campus::all(); // For filter dropdown

        return view('admin.classrooms.index', compact('stats', 'campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campuses = Campus::all();
        return view('admin.classrooms.create', compact('campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'campus_id' => 'required|exists:campuses,id',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
            'type' => 'required|in:Lecture Hall,Lab,Classroom,Online',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except(['image', 'translations']);
        $data['status'] = $request->has('status');
        $data['translations'] = $request->input('translations', []);
        
        // Use default locale name/description as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? '';
                        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                               ?? $request->input("translations.{$fallbackLocale}.description") 
                               ?? '';

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/classrooms'), $imageName);
            $data['image'] = 'uploads/classrooms/'.$imageName;
        }

        Classroom::create($data);

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        $campuses = Campus::all();
        return view('admin.classrooms.edit', compact('classroom', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'room_number' => 'required|string|max:255',
            'translations' => 'nullable|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.description' => 'nullable|string',
            'campus_id' => 'required|exists:campuses,id',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
            'type' => 'required|in:Lecture Hall,Lab,Classroom,Online',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except(['image', 'translations']);
        $data['status'] = $request->has('status');
        $data['translations'] = $request->input('translations', []);

        // Use default locale name/description as fallback for main columns
        $defaultLocale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');
        
        $data['name'] = $request->input("translations.{$defaultLocale}.name") 
                        ?? $request->input("translations.{$fallbackLocale}.name") 
                        ?? $classroom->name;
                        
        $data['description'] = $request->input("translations.{$defaultLocale}.description") 
                               ?? $request->input("translations.{$fallbackLocale}.description") 
                               ?? $classroom->description;

        if ($request->hasFile('image')) {
            // Delete old image
            if($classroom->image && file_exists(public_path($classroom->image))){
                @unlink(public_path($classroom->image));
            }

            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/classrooms'), $imageName);
            $data['image'] = 'uploads/classrooms/'.$imageName;
        }

        $classroom->update($data);

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        if($classroom->image && file_exists(public_path($classroom->image))){
            @unlink(public_path($classroom->image));
        }
        $classroom->delete();
        return response()->json(['success' => 'Classroom deleted successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        // Return JSON for modal
        return response()->json([
            'image' => $classroom->image ? asset($classroom->image) : null,
            'room_number' => $classroom->room_number,
            'name' => $classroom->getTranslatedName(),
            'description' => $classroom->getTranslatedDescription(),
            'type' => $classroom->type,
            'campus' => $classroom->campus,
            'building' => $classroom->building,
            'floor' => $classroom->floor,
            'capacity' => $classroom->capacity,
            'status' => $classroom->status ? 'Active' : 'Inactive'
        ]);
    }

    /**
     * Get classrooms by campus ID
     */
    public function getByCampus($campusId)
    {
        $classrooms = Classroom::where('campus_id', $campusId)->where('status', true)->get();
        return response()->json($classrooms);
    }
}
