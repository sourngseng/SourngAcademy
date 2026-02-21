<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    /**
     * Display a listing of the slides.
     */
    /**
     * Display a listing of the slides.
     */
    /**
     * Display a listing of the slides.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slide::orderBy('order')->orderBy('created_at', 'desc');
            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row) {
                    if($row->image_path){
                        return '<img src="'.asset($row->image_path).'" alt="Slide Image" class="img-thumbnail" style="height: 60px; width: 100px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">No Image</span>';
                })
                ->editColumn('title', function($row){
                    return $row->getTranslation('title');
                })
                ->editColumn('subtitle', function($row){
                    return $row->getTranslation('subtitle') ?? '-';
                })
                ->editColumn('status', function($row) {
                    if($row->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-secondary">Inactive</span>';
                    }
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.slides.edit', $row->id);
                    $deleteUrl = route('admin.slides.destroy', $row->id);
                    
                    $duplicateUrl = route('admin.slides.duplicate', $row->id);
                    
                    return '
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="' . $editUrl . '">
                                    <i class="fas fa-edit mr-2 text-primary"></i> Edit
                                </a>
                                <button class="dropdown-item duplicateSlide" data-url="' . $duplicateUrl . '">
                                    <i class="fas fa-copy mr-2 text-info"></i> Duplicate
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item deleteSlide" data-url="' . $deleteUrl . '">
                                    <i class="fas fa-trash mr-2 text-danger"></i> Delete
                                </button>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('admin.slides.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = config('app.available_locales');
        return view('admin.slides.create', compact('languages'));
    }

    /**
     * Store a newly created slide in storage.
     */
    public function store(Request $request)
    {
        $languages = array_keys(config('app.available_locales'));
        $defaultLocale = config('app.fallback_locale', 'en');
        
        $rules = [
            'title' => 'required|array',
            'subtitle' => 'nullable|array',
            'description' => 'nullable|array',
            'button_text' => 'nullable|array',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|url',
            'order' => 'integer',
        ];

        // Require title for default locale at least
        $rules["title.$defaultLocale"] = 'required|string|max:255';
        
        // Add basic validation for other locales if needed, or just let them be nullable/string
        foreach ($languages as $locale) {
             if ($locale !== $defaultLocale) {
                 $rules["title.$locale"] = 'nullable|string|max:255';
             }
        }

        $request->validate($rules);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['show_title'] = $request->has('show_title');
        $data['show_subtitle'] = $request->has('show_subtitle');
        $data['show_description'] = $request->has('show_description');
        $data['show_button'] = $request->has('show_button');
        $data['order'] = $request->order ?? 0;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/slides'), $imageName);
            $data['image_path'] = 'uploads/slides/'.$imageName;
        }

        Slide::create($data);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide created successfully.');
    }

    /**
     * Show the form for editing the specified slide.
     */
    public function edit($id)
    {
        $slide = Slide::findOrFail($id);
        $languages = config('app.available_locales');
        return view('admin.slides.edit', compact('slide', 'languages'));
    }

    /**
     * Update the specified slide in storage.
     */
    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);
        
        $languages = array_keys(config('app.available_locales'));
        $defaultLocale = config('app.fallback_locale', 'en');
        
        $rules = [
            'title' => 'required|array',
            'subtitle' => 'nullable|array',
            'description' => 'nullable|array',
            'button_text' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|url',
            'order' => 'integer',
        ];

        // Require title for default locale
        $rules["title.$defaultLocale"] = 'required|string|max:255';
        
        foreach ($languages as $locale) {
             if ($locale !== $defaultLocale) {
                 $rules["title.$locale"] = 'nullable|string|max:255';
             }
        }

        $request->validate($rules);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');
        $data['show_title'] = $request->has('show_title');
        $data['show_subtitle'] = $request->has('show_subtitle');
        $data['show_description'] = $request->has('show_description');
        $data['show_button'] = $request->has('show_button');
        $data['order'] = $request->order ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if($slide->image_path && file_exists(public_path($slide->image_path))){
                unlink(public_path($slide->image_path));
            }
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/slides'), $imageName);
            $data['image_path'] = 'uploads/slides/'.$imageName;
        }

        $slide->update($data);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Slide updated successfully.');
    }

    /**
     * Remove the specified slide from storage.
     */
    public function destroy($id)
    {
        $slide = Slide::find($id);
        if ($slide->image_path && file_exists(public_path($slide->image_path))) {
            unlink(public_path($slide->image_path));
        }
        $slide->delete();
        return response()->json(['success' => 'Slide deleted successfully.']);
    }

    /**
     * Duplicate the specified slide.
     */
    public function duplicate($id)
    {
        $slide = Slide::findOrFail($id);
        
        $newSlide = $slide->replicate();
        
        // Append (Copy) to titles
        $newTitle = $slide->title;
        if (is_array($newTitle)) {
           foreach ($newTitle as $locale => $value) {
               $newTitle[$locale] = $value . ' (Copy)';
           }
           $newSlide->title = $newTitle;
        }

        // Handle image duplication
        if ($slide->image_path && file_exists(public_path($slide->image_path))) {
            $extension = pathinfo($slide->image_path, PATHINFO_EXTENSION);
            $newImageName = time() . '_' . uniqid() . '.' . $extension;
            $newPath = 'uploads/slides/' . $newImageName;

            // Ensure directory exists
            if (!file_exists(public_path('uploads/slides'))) {
                mkdir(public_path('uploads/slides'), 0755, true);
            }

            copy(public_path($slide->image_path), public_path($newPath));
            $newSlide->image_path = $newPath;
        }

        $newSlide->is_active = false; // Set copy as inactive
        $newSlide->save();

        return response()->json(['success' => 'Slide duplicated successfully.']);
    }
}
