<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\BlogPost;
use App\Models\GuestReview;
use App\Models\Amenity;
use App\Models\Setting;
use App\Models\Service;
use App\Modules\Academic\Models\Program;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Models\DegreeLevel;
use App\Modules\Academic\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FrontendApiController extends Controller
{
    /**
     * Get hero slider slides
     */
    public function slides(Request $request): JsonResponse
    {
        if ($request->has('locale')) {
            app()->setLocale($request->input('locale'));
        }

        $slides = Slide::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->getTranslation('title'),
                    'subtitle' => $slide->getTranslation('subtitle'),
                    'description' => $slide->getTranslation('description') ?? 'Experience world-class hospitality and unforgettable moments.',
                    'image' => $slide->image_path ? asset($slide->image_path) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2070&q=80',
                    'button_text' => $slide->getTranslation('button_text') ?? 'Explore',
                    'button_link' => $slide->link_url ?? '/programs',
                    'show_title' => $slide->show_title,
                    'show_subtitle' => $slide->show_subtitle,
                    'show_description' => $slide->show_description,
                    'show_button' => $slide->show_button,
                ];
            });
        
        // If no slides exist, return default slides
        if ($slides->isEmpty()) {
            $slides = collect([
                [
                    'id' => 1,
                    'title' => 'Welcome to Our Luxury Hotel',
                    'subtitle' => 'Experience Excellence',
                    'description' => 'Discover the perfect blend of comfort and elegance in the heart of the city.',
                    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2070&q=80',
                    'button_text' => 'Explore Rooms',
                    'button_link' => '/rooms'
                ],
                [
                    'id' => 2,
                    'title' => 'Unparalleled Comfort',
                    'subtitle' => 'Your Home Away From Home',
                    'description' => 'Relax in our beautifully appointed rooms with stunning views.',
                    'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=2070&q=80',
                    'button_text' => 'Book Now',
                    'button_link' => '/rooms'
                ],
                [
                    'id' => 3,
                    'title' => 'World-Class Amenities',
                    'subtitle' => 'Luxury Redefined',
                    'description' => 'Enjoy our spa, pool, and fine dining experiences.',
                    'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=2070&q=80',
                    'button_text' => 'View Services',
                    'button_link' => '/about'
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $slides
        ]);
    }

    /**
     * Get room types for filtering
     */
    public function roomTypes(): JsonResponse
    {
        $roomTypes = RoomType::withCount('rooms')
            ->get()
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->room_type_name,
                    'slug' => \Str::slug($type->room_type_name),
                    'description' => $type->description,
                    'base_price' => (float) $type->price_per_night,
                    'max_occupancy' => $type->max_occupancy,
                    'image' => $type->image ? asset($type->image) : 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80',
                    'rooms_count' => $type->rooms_count
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $roomTypes
        ]);
    }

   
    /**
     * Get testimonials/reviews
     */
    public function testimonials(): JsonResponse
    {
        $reviews = GuestReview::with('guestProfile')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'guest_name' => $review->guestProfile->name ?? 'Anonymous Guest',
                    'guest_country' => $review->guestProfile->country ?? 'International',
                    'guest_image' => $review->guestProfile->avatar ?? null,
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'comment' => $review->comment,
                    'stay_date' => $review->created_at->format('F Y'),
                    'room_type' => $review->room_type ?? null
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Get blog posts
     */
    public function blogPosts(Request $request): JsonResponse
    {
        $query = BlogPost::with('category')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc');
        
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        $posts = $query->paginate($request->get('per_page', 9));
        
        $transformed = $posts->getCollection()->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => strip_tags($post->excerpt ?? \Str::limit(strip_tags($post->content), 150)),
                'content' => $post->content,
                'image' => $post->image_path ? asset($post->image_path) : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=800&q=80',
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                    'slug' => $post->category->slug
                ] : null,
                'category_id' => $post->category_id,
                'author' => $post->author ? $post->author->name : ($post->author_name ?? 'Hotel Team'),
                'published_at' => $post->published_at?->format('M d, Y'),
                'read_time' => ceil(str_word_count(strip_tags($post->content ?? '')) / 200) . ' min read'
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $transformed,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ]
        ]);
    }

    /**
     * Get single blog post by slug
     */
    public function blogPost($slug): JsonResponse
    {
        $post = BlogPost::with('category', 'author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();
            
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
        
        // Increment views
        $post->increment('views_count');
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'image' => $post->image_path ? asset($post->image_path) : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=800&q=80',
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                    'slug' => $post->category->slug
                ] : null,
                'author' => $post->author ? $post->author->name : ($post->author_name ?? 'Hotel Team'),
                'published_at' => $post->published_at?->format('M d, Y'),
                'read_time' => ceil(str_word_count(strip_tags($post->content ?? '')) / 200) . ' min read'
            ]
        ]);
    }

    /**
     * Get blog categories
     */
    public function blogCategories(): JsonResponse
    {
        $categories = \App\Models\BlogCategory::withCount(['posts' => function ($q) {
            $q->where('is_published', true);
        }])->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get university settings
     */
    public function settings(Request $request): JsonResponse
    {
        if ($request->has('locale')) {
            app()->setLocale($request->input('locale'));
        }

        $settings = Setting::pluck('value', 'key')->toArray();

        $aboutValues = [];
        $currentLocale = app()->getLocale();
        
        for ($i = 1; $i <= 3; $i++) {
            $title = $settings['value_'.$i.'_title_'.$currentLocale] ?? $settings['value_'.$i.'_title_en'] ?? null;
            $desc = $settings['value_'.$i.'_desc_'.$currentLocale] ?? $settings['value_'.$i.'_desc_en'] ?? null;
            
            $icon = $settings['value_'.$i.'_icon'] ?? null;
            
            if ($title || $desc) {
                $aboutValues[] = [
                    'title' => $title,
                    'desc' => $desc,
                    'icon' => $icon
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                // University branding
                'hotel_name' => $settings['university_name'] ?? $settings['hotel_name'] ?? 'Sourng Academy',
                'university_name' => $settings['university_name'] ?? 'Sourng Academy',
                'university_short_name' => $settings['university_short_name'] ?? $settings['university_name'] ?? 'Sourng Academy',
                'hotel_tagline' => $settings['university_tagline'] ?? $settings['hotel_tagline'] ?? 'Excellence in Education',
                'university_tagline' => $settings['university_tagline'] ?? 'Excellence in Education',
                
                // Contact information
                'phone' => $settings['university_phone'] ?? $settings['phone'] ?? '+855 12 345 678',
                'email' => $settings['university_email'] ?? $settings['email'] ?? 'info@sourng.edu',
                'address' => $settings['university_address'] ?? $settings['address'] ?? 'Phnom Penh, Cambodia',
                
                // Social media (use existing or university-specific)
                'facebook' => $settings['facebook_url'] ?? $settings['facebook'] ?? '#',
                'instagram' => $settings['instagram_url'] ?? $settings['instagram'] ?? '#',
                'twitter' => $settings['twitter_url'] ?? $settings['twitter'] ?? '#',
                'youtube' => $settings['youtube_url'] ?? $settings['youtube'] ?? '#',
                'linkedin' => $settings['linkedin_url'] ?? '#',
                
                // Logo and branding
                'logo' => isset($settings['university_logo']) && $settings['university_logo'] ? asset($settings['university_logo']) : (isset($settings['logo']) && $settings['logo'] ? asset($settings['logo']) : null),
                'favicon' => isset($settings['favicon']) && $settings['favicon'] ? asset($settings['favicon']) : null,
                'logo_dark' => isset($settings['logo_dark']) && $settings['logo_dark'] ? asset($settings['logo_dark']) : null,
                'logo_bright' => isset($settings['logo_bright']) && $settings['logo_bright'] ? asset($settings['logo_bright']) : null,
                'primary_color' => $settings['primary_color'] ?? '#3b82f6',
                
                // University specific
                'academic_year' => $settings['academic_year_current'] ?? '2025-2026',
                'semester' => $settings['semester_current'] ?? 'Semester 1',
                'founded_year' => $settings['founded_year'] ?? '2016',
                'city' => $settings['city'] ?? 'Phnom Penh',
                
                // About content
                'about_text' => $settings['about_text_'.app()->getLocale()] ?? $settings['about_text_en'] ?? $settings['about_description'] ?? null,
                'about_title' => $settings['about_title_'.app()->getLocale()] ?? $settings['about_title_en'] ?? 'About Us',
                'about_image' => isset($settings['about_image']) && $settings['about_image'] ? asset($settings['about_image']) : null,
                'years_experience' => $settings['years_experience'] ?? '10',
                'total_rooms' => $settings['total_rooms'] ?? '50',
                'total_guests' => $settings['total_guests'] ?? '15K+',
                'rating' => $settings['rating'] ?? '4.9',
                'about_values' => $aboutValues,
                
                // Font settings
                'primary_font' => $settings['primary_font'] ?? 'Inter',
                'khmer_font' => $settings['khmer_font'] ?? 'kantumruy',
                
                // Contact details
                'contact_details' => $settings['contact_details'] ?? null,
                'contact_map_iframe' => $settings['contact_map_iframe'] ?? null,

                // Ad Settings - Top Ad
                'blog_ad_top_type' => $settings['blog_ad_top_type'] ?? 'none',
                'blog_ad_top_code' => $settings['blog_ad_top_code'] ?? null,
                'blog_ad_top_image' => isset($settings['blog_ad_top_image']) ? asset($settings['blog_ad_top_image']) : null,
                'blog_ad_top_url' => $settings['blog_ad_top_url'] ?? '#',

                // Ad Settings - Bottom Ad
                'blog_ad_bottom_type' => $settings['blog_ad_bottom_type'] ?? 'none',
                'blog_ad_bottom_code' => $settings['blog_ad_bottom_code'] ?? null,
                'blog_ad_bottom_image' => isset($settings['blog_ad_bottom_image']) ? asset($settings['blog_ad_bottom_image']) : null,
                'blog_ad_bottom_url' => $settings['blog_ad_bottom_url'] ?? '#',

                // Ad Settings - Sidebar Top Ad
                'blog_sidebar_ad_top_type' => $settings['blog_sidebar_ad_top_type'] ?? 'none',
                'blog_sidebar_ad_top_code' => $settings['blog_sidebar_ad_top_code'] ?? null,
                'blog_sidebar_ad_top_image' => isset($settings['blog_sidebar_ad_top_image']) ? asset($settings['blog_sidebar_ad_top_image']) : null,
                'blog_sidebar_ad_top_url' => $settings['blog_sidebar_ad_top_url'] ?? '#',

                // Ad Settings - Sidebar Bottom Ad
                'blog_sidebar_ad_bottom_type' => $settings['blog_sidebar_ad_bottom_type'] ?? 'none',
                'blog_sidebar_ad_bottom_code' => $settings['blog_sidebar_ad_bottom_code'] ?? null,
                'blog_sidebar_ad_bottom_image' => isset($settings['blog_sidebar_ad_bottom_image']) ? asset($settings['blog_sidebar_ad_bottom_image']) : null,
                'blog_sidebar_ad_bottom_url' => $settings['blog_sidebar_ad_bottom_url'] ?? '#',

                // Social Login Settings
                'google_login_enabled' => ($settings['google_login_enabled'] ?? 'off') === 'on',
                'facebook_login_enabled' => ($settings['facebook_login_enabled'] ?? 'off') === 'on',
                'telegram_login_enabled' => ($settings['telegram_login_enabled'] ?? 'off') === 'on',
                'telegram_bot_username' => $settings['telegram_bot_username'] ?? null,
            ]
        ]);
    }



    /**
     * Get hotel services
     */
    public function services(): JsonResponse
    {
        try {
            $services = Service::where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'description' => $service->description,
                        'icon' => $service->icon,
                        'image' => $service->image_path ? asset($service->image_path) : null
                    ];
                });
        } catch (\Exception $e) {
            $services = collect([]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

 
    /**
     * Get translations for a specific locale
     */
    public function translations(string $locale): JsonResponse
    {
        // Validate locale
        $allowedLocales = ['en', 'km', 'zh', 'fr'];
        if (!in_array($locale, $allowedLocales)) {
            $locale = 'en';
        }
        
        $langFile = resource_path("lang/{$locale}.json");
        
        if (file_exists($langFile)) {
            $translations = json_decode(file_get_contents($langFile), true);
            
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'data' => $translations
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Translation file not found',
            'data' => []
        ], 404);
    }

    /**
     * Get footer pages for frontend
     */
    public function footerPages(): JsonResponse
    {
        $pages = \App\Models\Page::active()
            ->footer()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($page) {
                return [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $page->localized_title,
                    'icon' => $page->icon,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get header pages for frontend
     */
    public function headerPages(): JsonResponse
    {
        $pages = \App\Models\Page::active()
            ->header()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($page) {
                return [
                    'id' => $page->id,
                    'slug' => $page->slug,
                    'title' => $page->localized_title,
                    'icon' => $page->icon,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Get single page by slug
     */
    public function page($slug): JsonResponse
    {
        $page = \App\Models\Page::where('slug', $slug)->active()->first();
        
        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $page->id,
                'slug' => $page->slug,
                'title' => $page->localized_title,
                'content' => $page->localized_content,
                'meta_title' => $page->meta_title ?? $page->localized_title,
                'meta_description' => $page->meta_description,
                'icon' => $page->icon,
            ]
        ]);
    }
    /**
     * Get programs for frontend
     */
    public function programs(Request $request): JsonResponse
    {
        $query = Program::with(['department', 'degreeLevel']);
        
        if ($request->has('is_popular')) {
            $query->where('is_popular', true);
        }
    
        if ($request->has('degree_level') && !empty($request->degree_level)) {
            $levels = is_array($request->degree_level) ? $request->degree_level : explode(',', $request->degree_level);
            $query->whereHas('degreeLevel', function($q) use ($levels) {
                $q->whereIn('name', $levels);
            });
        }

        if ($request->has('duration') && !empty($request->duration)) {
            $durations = is_array($request->duration) ? $request->duration : explode(',', $request->duration);
            $query->whereIn('duration_years', $durations);
        }

        if ($request->has('department_id') && !empty($request->department_id)) {
            $deptIds = is_array($request->department_id) ? $request->department_id : explode(',', $request->department_id);
            $query->whereIn('department_id', $deptIds);
        }

        $limit = (int)$request->get('limit', 9);
        $offset = (int)$request->get('offset', 0);
        
        $total = $query->count();
        
        $programs = $query->orderBy('name')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($program) {
                return [
                    'id' => $program->id,
                    'name' => $program->name,
                    'code' => $program->code,
                    'description' => strip_tags($program->description) ?: 'Explore our ' . $program->name . ' program for a bright future.',
                    'degree_level' => $program->degreeLevel ? $program->degreeLevel->name : null,
                    'duration_years' => (float)$program->duration_years,
                    'total_credits' => $program->total_credits,
                    'image' => $program->feature_image ? asset($program->feature_image) : 'https://images.unsplash.com/photo-152305085306e-8c3d3e7d4f1a?auto=format&fit=crop&w=800&q=80',
                    'department' => $program->department ? [
                        'id' => $program->department->id,
                        'name' => $program->department->name,
                    ] : null
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $programs,
            'meta' => [
                'total' => $total,
                'has_more' => ($offset + $limit) < $total,
                'next_offset' => $offset + $limit
            ]
        ]);
    }

    /**
     * Get available filters for programs
     */
    public function programsFilters(): JsonResponse
    {
        $degreeLevels = DegreeLevel::where('status', true)->orderBy('sort_order')->pluck('name');
        $durations = Program::distinct()->orderBy('duration_years')->pluck('duration_years')->filter()->values();
        $departments = Department::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => [
                'degree_levels' => $degreeLevels,
                'durations' => $durations,
                'departments' => $departments
            ]
        ]);
    }

    /**
     * Get faculties for frontend
     */
    public function faculties(): JsonResponse
    {
        $faculties = Faculty::withCount('departments')
            ->get()
            ->map(function ($faculty) {
                return [
                    'id' => $faculty->id,
                    'name' => $faculty->getTranslatedName(),
                    'slug' => $faculty->slug ?? \Str::slug($faculty->name),
                    'code' => $faculty->code,
                    'dean_name' => $faculty->dean_name,
                    'description' => strip_tags($faculty->getTranslatedDescription()),
                    'image' => $faculty->feature_image ? asset($faculty->feature_image) : 'https://images.unsplash.com/photo-1541339907198-e08756eaa589?auto=format&fit=crop&w=800&q=80',
                    'icon' => $faculty->icon,
                    'departments_count' => $faculty->departments_count
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $faculties
        ]);
    }

    /**
     * Get single faculty by slug or ID
     */
    public function faculty($identifier): JsonResponse
    {
        $query = Faculty::with(['departments', 'campus']);
        
        if (is_numeric($identifier)) {
            $faculty = $query->find($identifier);
        } else {
            $faculty = $query->where('slug', $identifier)->first();
        }
            
        if (!$faculty) {
            return response()->json([
                'success' => false,
                'message' => 'Faculty not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $faculty->id,
                'name' => $faculty->getTranslatedName(),
                'slug' => $faculty->slug,
                'code' => $faculty->code,
                'dean_name' => $faculty->dean_name,
                'description' => $faculty->getTranslatedDescription(),
                'image' => $faculty->feature_image ? asset($faculty->feature_image) : 'https://images.unsplash.com/photo-1541339907198-e08756eaa589?auto=format&fit=crop&w=800&q=80',
                'icon' => $faculty->icon,
                'campus' => $faculty->campus ? [
                    'id' => $faculty->campus->id,
                    'name' => $faculty->campus->name_en,
                ] : null,
                'departments' => $faculty->departments->map(function($dept) {
                    return [
                        'id' => $dept->id,
                        'name' => $dept->getTranslatedName(),
                        'slug' => $dept->slug,
                        'image' => $dept->feature_image ? asset($dept->feature_image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                        'description' => strip_tags($dept->getTranslatedDescription()),
                    ];
                })
            ]
        ]);
    }

    /**
     * Get single department by slug
     */
    public function department($slug): JsonResponse
    {
        $dept = \App\Modules\Academic\Models\Department::with(['faculty', 'headOfDepartment'])
            ->where('slug', $slug)
            ->first();
            
        if (!$dept) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $dept->id,
                'name' => $dept->getTranslatedName(),
                'slug' => $dept->slug,
                'code' => $dept->code,
                'image' => $dept->feature_image ? asset($dept->feature_image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                'description' => $dept->getTranslatedDescription(),
                'mission' => $dept->getTranslatedMission(),
                'vision' => $dept->getTranslatedVision(),
                'goal' => $dept->getTranslatedGoal(),
                'email' => $dept->email,
                'phone' => $dept->phone,
                'working_hours' => $dept->working_hours,
                'faculty' => $dept->faculty ? [
                    'id' => $dept->faculty->id,
                    'name' => $dept->faculty->getTranslatedName(),
                    'slug' => $dept->faculty->slug,
                ] : null,
                'head_of_department' => $dept->headOfDepartment ? [
                    'id' => $dept->headOfDepartment->id,
                    'name' => $dept->headOfDepartment->name,
                    'avatar' => $dept->headOfDepartment->avatar ? asset($dept->headOfDepartment->avatar) : null,
                ] : null,
            ]
        ]);
    }

    /**
     * Get all campuses for frontend
     */
    public function campuses(): JsonResponse
    {
        $campuses = \App\Modules\Core\Models\Campus::latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $campuses->map(function($campus) {
                return [
                    'id' => $campus->id,
                    'name' => app()->getLocale() === 'km' ? $campus->name_km : $campus->name_en,
                    'slug' => $campus->slug ?: \Illuminate\Support\Str::slug($campus->name_en) . '-' . $campus->id,
                    'code' => $campus->code,
                    'short_name' => $campus->short_name,
                    'image' => $campus->feature_image ? asset($campus->feature_image) : 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                    'address' => $campus->address,
                    'phone' => $campus->phone,
                    'email' => $campus->email,
                    'latitude' => $campus->latitude,
                    'longitude' => $campus->longitude,
                ];
            })
        ]);
    }

    /**
     * Get single campus by slug
     */
    public function campusDetail(string $slug): JsonResponse
    {
        $campus = \App\Modules\Core\Models\Campus::with(['faculties'])->where('slug', $slug)->first();

        // Fallback: If not found by slug, check if it's a fallback "campus-{id}" pattern
        if (!$campus && str_starts_with($slug, 'campus-')) {
            $id = substr($slug, 7); // Remove "campus-" prefix
            if (is_numeric($id)) {
                $campus = \App\Modules\Core\Models\Campus::with(['faculties'])->find($id);
            }
        }
        
        if (!$campus) {
            return response()->json([
                'success' => false,
                'message' => 'Campus not found'
            ], 404);
        }

        // Return detail data (load faculties etc if needed)
        // Re-use logic or format as needed
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $campus->id,
                'name' => app()->getLocale() === 'km' ? $campus->name_km : $campus->name_en,
                'slug' => $campus->slug ?: 'campus-' . $campus->id,
                'code' => $campus->code,
                'image' => $campus->feature_image ? asset($campus->feature_image) : 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                'description' => $campus->description,
                'address' => $campus->address,
                    'phone' => $campus->phone,
                    'email' => $campus->email,
                    'latitude' => $campus->latitude,
                    'longitude' => $campus->longitude,
                'users' => $campus->users,
                'faculties' => $campus->faculties->map(function($faculty) {
                     return [
                         'id' => $faculty->id,
                         'name' => $faculty->getTranslatedName(),
                         'slug' => $faculty->slug,
                         'code' => $faculty->code,
                         'image' => $faculty->feature_image ? asset($faculty->feature_image) : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                         'departments_count' => $faculty->departments()->count()
                     ];
                })
            ]
        ]);
    }
}
