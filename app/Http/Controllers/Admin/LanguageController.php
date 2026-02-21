<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switch(Request $request, $locale)
    {
        // Get available locales from language files
        $langPath = resource_path('lang');
        $availableLocales = collect(File::glob($langPath . '/*.json'))
            ->map(fn($file) => basename($file, '.json'))
            ->toArray();
        
        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
        }
        
        return redirect()->back();
    }
    
    /**
     * Get available languages for display
     */
    public static function getAvailableLanguages(): array
    {
        $langPath = resource_path('lang');
        $languages = [];
        
        $files = File::glob($langPath . '/*.json');
        
        foreach ($files as $file) {
            $locale = basename($file, '.json');
            $languages[$locale] = [
                'name' => config('app.available_locales')[$locale]['name'] ?? strtoupper($locale),
                'flag' => config('app.available_locales')[$locale]['flag'] ?? '🌐',
            ];
        }
        
        return $languages;
    }
}
