<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Page::orderBy('sort_order')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title_display', function ($row) {
                    return $row->getTitle('en') ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    }
                    return '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('locations', function ($row) {
                    $badges = '';
                    if ($row->show_in_footer) {
                        $badges .= '<span class="badge badge-info mr-1">Footer</span>';
                    }
                    if ($row->show_in_header) {
                        $badges .= '<span class="badge badge-primary">Header</span>';
                    }
                    return $badges ?: '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('icon_preview', function ($row) {
                    if ($row->icon) {
                        return '<i class="' . e($row->icon) . ' fa-lg"></i>';
                    }
                    return '-';
                })
                ->addColumn('translations', function ($row) {
                    $titles = $row->title;
                    if (is_string($titles)) {
                        $titles = json_decode($titles, true) ?? [];
                    }
                    $badges = '';
                    foreach (array_keys($titles) as $locale) {
                        $badges .= '<span class="badge badge-secondary mr-1">' . strtoupper($locale) . '</span>';
                    }
                    return $badges ?: '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.pages.edit', $row->id);
                    $deleteUrl = route('admin.pages.destroy', $row->id);
                    $viewUrl = url('/' . $row->slug);
                    
                    return '
                        <a href="' . $viewUrl . '" class="btn btn-sm btn-info" target="_blank" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="' . $deleteUrl . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this page?\')">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'locations', 'icon_preview', 'translations', 'action'])
                ->make(true);
        }

        return view('admin.pages.index');
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        $languages = Page::getAvailableLanguages();
        return view('admin.pages.create', compact('languages'));
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'show_in_footer' => 'boolean',
            'show_in_header' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Generate slug from English title if not provided
        $slug = $validated['slug'] ?? Str::slug($validated['title']['en']);
        
        // Ensure slug is unique
        $baseSlug = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Handle checkboxes
        $data = [
            'slug' => $slug,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? [],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'show_in_footer' => $request->has('show_in_footer'),
            'show_in_header' => $request->has('show_in_header'),
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        Page::create($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $languages = Page::getAvailableLanguages();
        
        // Ensure title and content are arrays
        $titles = $page->title;
        $contents = $page->content;
        
        if (is_string($titles)) {
            $titles = json_decode($titles, true) ?? ['en' => $titles];
        }
        if (is_string($contents)) {
            $contents = json_decode($contents, true) ?? ['en' => $contents];
        }
        
        return view('admin.pages.edit', compact('page', 'languages', 'titles', 'contents'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $id,
            'content' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'show_in_footer' => 'boolean',
            'show_in_header' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Generate slug from English title if not provided
        $slug = $validated['slug'] ?? Str::slug($validated['title']['en']);

        // Handle checkboxes
        $data = [
            'slug' => $slug,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? [],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'show_in_footer' => $request->has('show_in_footer'),
            'show_in_header' => $request->has('show_in_header'),
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
