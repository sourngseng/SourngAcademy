<?php

namespace App\Http\Controllers\Student;

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
        
        // Fetch Student's Enrolled Classes and associated Subjects with Session Plans
        $classes = \App\Modules\Academic\Models\ClassModel::whereHas('students', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->with(['subject.sessionPlans' => function($q) {
                $q->whereNull('parent_id')->orderBy('created_at', 'asc');
            }])
            ->get();

        // Student-specific statistics
        $stats = [
            'enrollments' => $classes->count(),
            'attendance_rate' => '0%', 
            'gpa' => '0.00',
            'outstanding_fees' => '$0.00',
        ];

        // Recent announcements/news
        $recentPosts = BlogPost::with('author')
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact('stats', 'recentPosts', 'classes'));
    }
}
