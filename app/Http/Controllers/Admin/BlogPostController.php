<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        // Get statistics
        $totalPosts = BlogPost::count();
        $publishedPosts = BlogPost::where('is_published', true)->count();
        $draftPosts = BlogPost::where('is_published', false)->count();
        $totalViews = BlogPost::sum('views_count');
        
        // Get categories for filter
        $categories = BlogCategory::all();
        
        if ($request->ajax()) {
            $query = BlogPost::with(['user', 'category'])->select('blog_posts.*');
            
            // Apply filters
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('status')) {
                $query->where('is_published', $request->status == 'published' ? 1 : 0);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('published_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('published_at', '<=', $request->date_to);
            }
            
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function($row) {
                    if($row->image_path) {
                        return '<img src="'.asset($row->image_path).'" alt="Post Image" class="post-image">';
                    } else {
                        return '<div class="post-image bg-secondary d-flex align-items-center justify-content-center"><i class="fas fa-image text-white"></i></div>';
                    }
                })
                ->editColumn('title', function($row) {
                    $category = $row->category ? $row->category->name : 'Uncategorized';
                    $categoryBadge = '<span class="badge badge-info mr-2"><i class="fas fa-folder mr-1"></i>'.$category.'</span>';
                    $viewsBadge = '<span class="badge badge-secondary"><i class="fas fa-eye mr-1"></i>'.number_format($row->views_count).' views</span>';
                    
                    return '<div class="post-title">'.$row->title.'</div>
                            <div class="post-meta">'.$categoryBadge.$viewsBadge.'</div>';
                })
                ->editColumn('status', function($row) {
                    if($row->is_published) {
                        return '<span class="badge badge-success">Published</span>';
                    } else {
                        return '<span class="badge badge-warning">Draft</span>';
                    }
                })
                ->editColumn('published_at', function($row) {
                    return $row->published_at ? $row->published_at->format('M d, Y H:i') : '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.blog.edit', $row->id).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $btn = $btn.' <a href="javascript:void(0)" data-url="'.route('admin.blog.destroy', $row->id).'" class="btn btn-danger btn-sm deletePost"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['image', 'title', 'status', 'action'])
                ->make(true);
        }
        return view('admin.blog.index', compact('totalPosts', 'publishedPosts', 'draftPosts', 'totalViews', 'categories'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $slug = Str::slug($request->title);
        // Ensure unique slug
        if (BlogPost::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $data = [
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'is_published' => $request->has('is_published'),
            'published_at' => $request->published_at,
        ];

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/blog'), $imageName);
            $data['image_path'] = 'uploads/blog/'.$imageName;
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit($id)
    {
        $post = BlogPost::with(['category'])->findOrFail($id);
        $categories = BlogCategory::all();
        
        // Get reader statistics
        $readerLocations = $post->reader_locations ?? [];
        $readerDeviceTypes = $post->reader_device_types ?? [];
        
        // Count unique locations
        $locationCounts = array_count_values($readerLocations);
        arsort($locationCounts);
        $topLocations = array_slice($locationCounts, 0, 5, true);
        
        // Count device types
        $deviceCounts = array_count_values($readerDeviceTypes);
        arsort($deviceCounts);
        
        return view('admin.blog.edit', compact('post', 'categories', 'topLocations', 'deviceCounts'));
    }

    /**
     * Update the specified blog post in storage.
     */
    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'is_published' => $request->has('is_published'),
            'published_at' => $request->published_at,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if($post->image_path && file_exists(public_path($post->image_path))){
                unlink(public_path($post->image_path));
            }
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/blog'), $imageName);
            $data['image_path'] = 'uploads/blog/'.$imageName;
        }

        $post->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy($id)
    {
        $post = BlogPost::find($id);
        if ($post->image_path && file_exists(public_path($post->image_path))) {
            unlink(public_path($post->image_path));
        }
        $post->delete();
        return response()->json(['success' => 'Post deleted successfully.']);
    }
    
    /**
     * Get statistics for a specific blog post.
     */
    public function getStatistics($id)
    {
        $post = BlogPost::findOrFail($id);
        
        return response()->json([
            'views' => $post->views_count,
            'locations' => $post->reader_locations ?? [],
            'devices' => $post->reader_device_types ?? [],
        ]);
    }
}
