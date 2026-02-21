<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Fetch Teacher's Classes and associated Subjects with Session Plans
        $classes = \App\Models\ClassSubject::where('teacher_id', $user->id)
            ->with(['class.campus', 'subject.sessionPlans' => function($q) {
                $q->whereNull('parent_id')->orderBy('created_at', 'asc');
            }])
            ->get();

        // Teacher-specific statistics
        $classIds = $classes->pluck('class_id')->unique();
        $stats = [
            'total_students' => \App\Modules\Academic\Models\Enrollment::whereIn('class_id', $classIds)->count(),
            'total_classes' => $classes->count(),
            'pending_assignments' => 0,
            'upcoming_exams' => 0,
        ];

        // Recent announcements/news
        $recentPosts = BlogPost::with('author')
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('stats', 'recentPosts', 'classes'));
    }
}
