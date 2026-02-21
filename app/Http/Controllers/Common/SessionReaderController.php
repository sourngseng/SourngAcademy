<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\SessionPlan;
use Illuminate\Support\Facades\Auth;

class SessionReaderController extends Controller
{
    /**
     * Display the specified session plan in the reader view.
     * This controller is shared across Admin, Teacher, and Student portals.
     */
    public function show($id)
    {
        $sessionPlan = SessionPlan::with(['campus', 'program', 'subject', 'academicYear', 'semester', 'author', 'parent', 'children'])
            ->findOrFail($id);
            
        $roadmapSessions = SessionPlan::where('subject_id', $sessionPlan->subject_id)
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        $videoEmbedUrl = $this->getYouTubeEmbedUrl($sessionPlan->video_url);
            
        return view('admin.session-plans.show', compact('sessionPlan', 'roadmapSessions', 'videoEmbedUrl'));
    }

    /**
     * Parse YouTube URL to get the embeddable link.
     */
    private function getYouTubeEmbedUrl($url)
    {
        if (!$url) return null;

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return "https://www.youtube.com/embed/" . $matches[1];
        }

        return null;
    }
}
