<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     * Since frontend uses Vue.js SPA, we return the main app view
     * and let Vue Router handle the page display.
     */
    public function show($slug)
    {
        // Verify the page exists and is active
        $page = Page::findBySlug($slug);
        
        if (!$page) {
            abort(404);
        }
        
        // Return the Vue SPA - Vue Router will handle /page/:slug
        return view('frontend.app');
    }
}
