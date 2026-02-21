<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    /**
     * Display a listing of the services.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::query();
            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function($row){
                    if($row->icon){
                        // Display as Font Awesome icon
                        return '<i class="fas '.$row->icon.' fa-2x"></i>';
                    }
                    return '<i class="fas fa-cogs fa-2x"></i>'; // Default icon if no icon is set
                })
                ->addColumn('image', function($row){
                     if($row->image_path){
                        return '<img src="'.asset($row->image_path).'" width="50" class="img-thumbnail" />';
                    }
                    return '';
                })
                ->editColumn('description', function($row){
                    return $row->description ?? '-';
                })
                ->editColumn('status', function($row) {
                    if($row->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-secondary">Inactive</span>';
                    }
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editService"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-url="'.route('admin.services.destroy', $row->id).'" class="btn btn-danger btn-sm deleteService"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['icon', 'image', 'status', 'action'])
                ->make(true);
        }
        return view('admin.services.index');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle icon: if it's a file upload, process it; otherwise, keep it as Font Awesome class
        if ($request->hasFile('icon')) {
            $imageName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/services'), $imageName);
            $data['icon'] = 'uploads/services/'.$imageName;
        } elseif ($request->has('icon') && !empty($request->icon)) {
            // It's a Font Awesome class name (string like 'fa-cogs')
            $data['icon'] = $request->icon;
        }

        if ($request->hasFile('image')) {
            $imageName = time().'_img.'.$request->image->extension();  
            $request->image->move(public_path('uploads/services'), $imageName);
            $data['image_path'] = 'uploads/services/'.$imageName;
        }
        
        Service::create($data);

        return response()->json(['success' => 'Service created successfully.']);
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Handle icon: if it's a file upload, process it; otherwise, keep it as Font Awesome class
        if ($request->hasFile('icon')) {
            // Delete old icon if it's a file and exists
            if($service->icon && strpos($service->icon, 'uploads') !== false && file_exists(public_path($service->icon))){
                unlink(public_path($service->icon));
            }
            $imageName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/services'), $imageName);
            $data['icon'] = 'uploads/services/'.$imageName;
        } elseif ($request->has('icon') && !empty($request->icon)) {
            // It's a Font Awesome class name (string like 'fa-cogs')
            // Delete old icon file if it exists and we're switching to Font Awesome
            if($service->icon && strpos($service->icon, 'uploads') !== false && file_exists(public_path($service->icon))){
                unlink(public_path($service->icon));
            }
            $data['icon'] = $request->icon;
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if($service->image_path && file_exists(public_path($service->image_path))){
                unlink(public_path($service->image_path));
            }
            $imageName = time().'_img.'.$request->image->extension();  
            $request->image->move(public_path('uploads/services'), $imageName);
            $data['image_path'] = 'uploads/services/'.$imageName;
        }

        $service->update($data);

        return response()->json(['success' => 'Service updated successfully.']);
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service->image_path && file_exists(public_path($service->image_path))) {
            unlink(public_path($service->image_path));
        }
        if ($service->icon && strpos($service->icon, 'uploads') !== false && file_exists(public_path($service->icon))) {
            unlink(public_path($service->icon));
        }
        $service->delete();
        return response()->json(['success' => 'Service deleted successfully.']);
    }
}
