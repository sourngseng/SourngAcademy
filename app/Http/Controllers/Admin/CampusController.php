<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Campus;
use App\Modules\Core\Models\CampusImage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class CampusController extends Controller
{
    /**
     * Display a listing of the campuses.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }

        if ($request->ajax()) {
            $data = Campus::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.campuses.edit', $row->id).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'" data-url="'.route('admin.campuses.destroy', $row->id).'"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->editColumn('is_active', function($row){
                    $class = $row->is_active ? 'badge-success' : 'badge-danger';
                    $text  = $row->is_active ? 'Active' : 'Inactive';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->editColumn('name', function($row){
                    $name   = $row->name_en;
                    $nameKm = $row->getTranslatedName('km');
                    return '<strong>'.$name.'</strong><br><small class="text-muted">'.$nameKm.'</small>';
                })
                ->rawColumns(['action', 'is_active', 'name'])
                ->make(true);
        }

        return view('admin.campuses.index');
    }

    /**
     * Show the form for creating a new campus.
     */
    public function create()
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }
        $languages = config('app.available_locales');
        return view('admin.campuses.create', compact('languages'));
    }

    /**
     * Store a newly created campus in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }

        $languages = config('app.available_locales');
        $fallback  = config('app.fallback_locale', 'en');

        $request->validate([
            'code'          => 'required|string|max:20|unique:campuses,code',
            'slug'          => 'nullable|string|max:255|unique:campuses,slug',
            "name_{$fallback}" => 'required|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
        ]);

        $data = $request->except(array_merge(
            ['_token', 'feature_image', 'payment_qr_image', 'gallery_images'],
            collect($languages)->keys()->flatMap(fn($code) => ["name_{$code}", "description_{$code}", "address_{$code}"])->toArray()
        ));

        // Slug auto-generate
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($request->input("name_{$fallback}"));
        }

        // Build translations array
        $translations = [];
        foreach ($languages as $code => $lang) {
            $translations[$code] = [
                'name'        => $request->input("name_{$code}"),
                'description' => $request->input("description_{$code}"),
                'address'     => $request->input("address_{$code}"),
            ];
        }
        $data['translations'] = $translations;

        // Primary name columns (backward compat)
        $data['name_en'] = $request->input("name_en");
        $data['name_km'] = $request->input("name_km");

        // Handle Feature Image
        if ($request->hasFile('feature_image')) {
            $imageName = 'feature_'.time().'.'.$request->feature_image->extension();
            $request->feature_image->move(public_path('uploads/campuses'), $imageName);
            $data['feature_image'] = 'uploads/campuses/'.$imageName;
        }

        // Handle Payment QR Image
        if ($request->hasFile('payment_qr_image')) {
            $qrName = 'qr_'.time().'.'.$request->payment_qr_image->extension();
            $request->payment_qr_image->move(public_path('uploads/campuses/qr'), $qrName);
            $data['payment_qr_image'] = 'uploads/campuses/qr/'.$qrName;
        }

        $campus = Campus::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_'.time().'_'.$index.'.'.$image->extension();
                $image->move(public_path('uploads/campuses/gallery'), $imageName);
                $campus->images()->create([
                    'image_path' => 'uploads/campuses/gallery/'.$imageName,
                    'order'      => $index,
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Campus created successfully.']);
        }

        return redirect()->route('admin.campuses.index')->with('success', 'Campus created successfully.');
    }

    /**
     * Show the form for editing the specified campus.
     */
    public function edit(Campus $campus)
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }
        $languages = config('app.available_locales');
        return view('admin.campuses.edit', compact('campus', 'languages'));
    }

    /**
     * Update the specified campus in storage.
     */
    public function update(Request $request, Campus $campus)
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }

        $languages = config('app.available_locales');
        $fallback  = config('app.fallback_locale', 'en');

        $request->validate([
            'code'          => 'required|string|max:20|unique:campuses,code,'.$campus->id,
            'slug'          => 'nullable|string|max:255|unique:campuses,slug,'.$campus->id,
            "name_{$fallback}" => 'required|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
        ]);

        $data = $request->except(array_merge(
            ['_token', '_method', 'feature_image', 'payment_qr_image', 'gallery_images', 'delete_images', 'remove_payment_qr'],
            collect($languages)->keys()->flatMap(fn($code) => ["name_{$code}", "description_{$code}", "address_{$code}"])->toArray()
        ));

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($request->input("name_{$fallback}"));
        }

        // Build translations array
        $existing     = $campus->translations ?? [];
        $translations = [];
        foreach ($languages as $code => $lang) {
            $translations[$code] = [
                'name'        => $request->input("name_{$code}") ?? ($existing[$code]['name'] ?? null),
                'description' => $request->input("description_{$code}") ?? ($existing[$code]['description'] ?? null),
                'address'     => $request->input("address_{$code}") ?? ($existing[$code]['address'] ?? null),
            ];
        }
        $data['translations'] = $translations;

        // Primary name columns (backward compat)
        $data['name_en'] = $request->input("name_en");
        $data['name_km'] = $request->input("name_km");

        // Handle Feature Image
        if ($request->hasFile('feature_image')) {
            if ($campus->feature_image && file_exists(public_path($campus->feature_image))) {
                @unlink(public_path($campus->feature_image));
            }
            $imageName = 'feature_'.time().'.'.$request->feature_image->extension();
            $request->feature_image->move(public_path('uploads/campuses'), $imageName);
            $data['feature_image'] = 'uploads/campuses/'.$imageName;
        }

        // Handle QR image removal checkbox
        if ($request->has('remove_payment_qr') && $request->remove_payment_qr == '1') {
            if ($campus->payment_qr_image && file_exists(public_path($campus->payment_qr_image))) {
                @unlink(public_path($campus->payment_qr_image));
            }
            $data['payment_qr_image'] = null;
        }

        // Handle Payment QR Image
        if ($request->hasFile('payment_qr_image')) {
            if ($campus->payment_qr_image && file_exists(public_path($campus->payment_qr_image))) {
                @unlink(public_path($campus->payment_qr_image));
            }
            $qrName = 'qr_'.time().'.'.$request->payment_qr_image->extension();
            $request->payment_qr_image->move(public_path('uploads/campuses/qr'), $qrName);
            $data['payment_qr_image'] = 'uploads/campuses/qr/'.$qrName;
        }

        $campus->update($data);

        // Handle Gallery Images Addition
        if ($request->hasFile('gallery_images')) {
            $lastOrder = $campus->images()->max('order') ?? -1;
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = 'gallery_'.time().'_'.$index.'.'.$image->extension();
                $image->move(public_path('uploads/campuses/gallery'), $imageName);
                $campus->images()->create([
                    'image_path' => 'uploads/campuses/gallery/'.$imageName,
                    'order'      => $lastOrder + $index + 1,
                ]);
            }
        }

        // Handle Gallery Images Deletion (Ajax)
        if ($request->has('delete_images')) {
            $imagesToDelete = CampusImage::whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $img) {
                if (file_exists(public_path($img->image_path))) {
                    @unlink(public_path($img->image_path));
                }
                $img->delete();
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Campus updated successfully.']);
        }

        return redirect()->route('admin.campuses.index')->with('success', 'Campus updated successfully.');
    }

    /**
     * Remove the specified campus from storage.
     */
    public function destroy(Campus $campus)
    {
        if (!Gate::allows('manage_campuses')) {
            abort(403);
        }

        if ($campus->feature_image && file_exists(public_path($campus->feature_image))) {
            @unlink(public_path($campus->feature_image));
        }

        foreach ($campus->images as $img) {
            if (file_exists(public_path($img->image_path))) {
                @unlink(public_path($img->image_path));
            }
        }

        $campus->delete();

        return response()->json(['success' => 'Campus deleted successfully.']);
    }
}
