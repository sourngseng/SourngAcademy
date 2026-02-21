<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BlogCategory::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function($row){
                    if($row->icon){
                        // Display as Font Awesome icon
                        return '<i class="fas '.$row->icon.' fa-2x"></i>';
                    }
                    return '<i class="fas fa-question-circle fa-2x"></i>'; // Default icon if no icon is set
                })
                ->addColumn('image', function($row){
                     if($row->image){
                        return '<img src="'.asset($row->image).'" width="50" class="img-thumbnail" />';
                    }
                    return '';
                })
                ->editColumn('description', function($row){
                    return $row->description ?? '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editCategory"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-url="'.route('admin.blog-categories.destroy', $row->id).'" class="btn btn-danger btn-sm deleteCategory"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['icon', 'image', 'action'])
                ->make(true);
        }
        return view('admin.blog_categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle icon: if it's a file upload, process it; otherwise, keep it as Font Awesome class
        if ($request->hasFile('icon')) {
            $imageName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/blog_categories'), $imageName);
            $data['icon'] = 'uploads/blog_categories/'.$imageName;
        } elseif ($request->has('icon') && !empty($request->icon)) {
            // It's a Font Awesome class name (string like 'fa-tag')
            $data['icon'] = $request->icon;
        }

        if ($request->hasFile('image')) {
            $imageName = time().'_img.'.$request->image->extension();  
            $request->image->move(public_path('uploads/blog_categories'), $imageName);
            $data['image'] = 'uploads/blog_categories/'.$imageName;
        }
        
        BlogCategory::create($data);

        return response()->json(['success' => 'Category created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        return response()->json($blogCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle icon: if it's a file upload, process it; otherwise, keep it as Font Awesome class
        if ($request->hasFile('icon')) {
            // Delete old icon if it's a file and exists
            if($blogCategory->icon && strpos($blogCategory->icon, 'uploads') !== false && file_exists(public_path($blogCategory->icon))){
                unlink(public_path($blogCategory->icon));
            }
            $imageName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/blog_categories'), $imageName);
            $data['icon'] = 'uploads/blog_categories/'.$imageName;
        } elseif ($request->has('icon') && !empty($request->icon)) {
            // It's a Font Awesome class name (string like 'fa-tag')
            // Delete old icon file if it exists and we're switching to Font Awesome
            if($blogCategory->icon && strpos($blogCategory->icon, 'uploads') !== false && file_exists(public_path($blogCategory->icon))){
                unlink(public_path($blogCategory->icon));
            }
            $data['icon'] = $request->icon;
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if($blogCategory->image && file_exists(public_path($blogCategory->image))){
                unlink(public_path($blogCategory->image));
            }
            $imageName = time().'_img.'.$request->image->extension();  
            $request->image->move(public_path('uploads/blog_categories'), $imageName);
            $data['image'] = 'uploads/blog_categories/'.$imageName;
        }

        $blogCategory->update($data);

        return response()->json(['success' => 'Category updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        BlogCategory::find($id)->delete();
        return response()->json(['success' => 'Category deleted successfully.']);
    }
}
