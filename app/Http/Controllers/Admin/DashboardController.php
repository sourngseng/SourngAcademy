<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Date filters
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : null;
        $dateTo = $request->date_to ? Carbon::parse($request->date_to)->endOfDay() : null;

        // Build university statistics
        $stats = [
            // User Statistics
            'total_students' => User::accessibleBy($user)
                ->where('role', 'student')
                ->count(),
            
            'total_teachers' => User::accessibleBy($user)
                ->where('role', 'teacher')
                ->count(),
            
            'total_staff' => User::accessibleBy($user)
                ->where('role', 'staff')
                ->count(),
            
            'total_users' => User::accessibleBy($user)->count(),
            
            // Content Statistics
            'total_posts' => BlogPost::when($dateFrom && $dateTo, function($query) use ($dateFrom, $dateTo) {
                    return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                })->count(),
                
            'published_posts' => BlogPost::where('is_published', true)
                ->when($dateFrom && $dateTo, function($query) use ($dateFrom, $dateTo) {
                    return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                })->count(),
                
            'total_pages' => Page::count(),
            'total_services' => Service::count(),
            
            // Placeholder for future financial data
            'month_revenue' => 0,
        ];

        // Recent blog posts
        $recentPosts = BlogPost::with('author')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent users
        $recentUsers = User::accessibleBy($user)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly data for chart (placeholder - can be blog posts count)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $postsCount = BlogPost::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
                
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => $postsCount * 100 // Placeholder calculation
            ];
        }

        // Empty collections for unused variables expected by view
        $subjects = collect();

        return view('admin.university-dashboard', compact(
            'stats',
            'recentPosts',
            'recentUsers',
            'monthlyRevenue',
            'subjects'
        ));
    }
}
