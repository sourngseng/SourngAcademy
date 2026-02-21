<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index()
    {
        if (!Gate::allows('manage_settings')) {
            abort(403);
        }

        $settings = Setting::getAll();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!Gate::allows('manage_settings')) {
            abort(403);
        }

        $request->validate([
            'university_name' => 'required|string|max:255',
            'university_email' => 'required|email',
            'university_phone' => 'nullable|string',
            'university_address' => 'nullable|string',
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
        ]);

        // Handle logo upload
        if ($request->hasFile('university_logo')) {
            $oldLogo = Setting::get('university_logo');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }

            $logoName = 'logo_'.time().'.'.$request->university_logo->extension();
            $request->university_logo->move(public_path('uploads/settings'), $logoName);
            $logoPath = 'uploads/settings/'.$logoName;
            Setting::set('university_logo', $logoPath);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon && file_exists(public_path($oldFavicon))) {
                @unlink(public_path($oldFavicon));
            }

            $faviconName = 'favicon_'.time().'.'.$request->favicon->extension();
            $request->favicon->move(public_path('uploads/settings'), $faviconName);
            Setting::set('favicon', 'uploads/settings/'.$faviconName);
        }

        // Handle dark logo upload
        if ($request->hasFile('logo_dark')) {
            $oldLogo = Setting::get('logo_dark');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }

            $logoName = 'logo_dark_'.time().'.'.$request->logo_dark->extension();
            $request->logo_dark->move(public_path('uploads/settings'), $logoName);
            Setting::set('logo_dark', 'uploads/settings/'.$logoName);
        }

        // Handle bright logo upload
        if ($request->hasFile('logo_bright')) {
            $oldLogo = Setting::get('logo_bright');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                @unlink(public_path($oldLogo));
            }

            $logoName = 'logo_bright_'.time().'.'.$request->logo_bright->extension();
            $request->logo_bright->move(public_path('uploads/settings'), $logoName);
            Setting::set('logo_bright', 'uploads/settings/'.$logoName);
        }

        // Handle Ad Image Uploads
        $adImageKeys = [
            'blog_ad_top_image', 
            'blog_ad_bottom_image', 
            'blog_sidebar_ad_top_image', 
            'blog_sidebar_ad_bottom_image'
        ];

        foreach ($adImageKeys as $imageKey) {
            if ($request->hasFile($imageKey)) {
                $oldImage = Setting::get($imageKey);
                // Optional: Delete old image if exists
                // if ($oldImage && file_exists(public_path($oldImage))) {
                //    @unlink(public_path($oldImage));
                // }

                $file = $request->file($imageKey);
                $filename = 'ad_'.uniqid().'.'.$file->extension();
                $file->move(public_path('uploads/ads'), $filename);
                Setting::set($imageKey, 'uploads/ads/'.$filename);
            }
        }

        // Save general settings
        $generalSettings = [
            'university_name', 'university_short_name', 'university_email', 
            'university_phone', 'university_address', 'university_website',
            'primary_font', 'khmer_font', 'social_facebook', 'social_telegram',
            'timetable_issued_date', 'director_name'
        ];

        foreach ($generalSettings as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Save branding settings
        $brandingSettings = [
            'primary_color', 'secondary_color', 'accent_color'
        ];

        foreach ($brandingSettings as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Save ad settings
        $adSettings = [
            // Ad Types (none, code, image)
            'blog_ad_top_type', 'blog_ad_bottom_type', 
            'blog_sidebar_ad_top_type', 'blog_sidebar_ad_bottom_type',
            
            // Ad Codes
            'blog_ad_top_code', 'blog_ad_bottom_code', 
            'blog_sidebar_ad_top_code', 'blog_sidebar_ad_bottom_code',
            
            // Ad URLs
            'blog_ad_top_url', 'blog_ad_bottom_url', 
            'blog_sidebar_ad_top_url', 'blog_sidebar_ad_bottom_url',
            
            // About Page Settings
            'founded_year', 'city', 'years_experience', 'total_rooms', 'total_guests', 'rating',
            
            // Value Icons
            'value_1_icon', 'value_2_icon', 'value_3_icon'
        ];
        
        // Add dynamic locale fields for About Page
        foreach (config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $name) {
            $adSettings[] = 'about_title_' . $locale;
            $adSettings[] = 'about_text_' . $locale;
            
            // About Values
            $adSettings[] = 'value_1_title_' . $locale;
            $adSettings[] = 'value_1_desc_' . $locale;
            $adSettings[] = 'value_2_title_' . $locale;
            $adSettings[] = 'value_2_desc_' . $locale;
            $adSettings[] = 'value_3_title_' . $locale;
            $adSettings[] = 'value_3_desc_' . $locale;
        }

        foreach ($adSettings as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Save Social Auth Settings
        $socialSettings = [
            'google_client_id', 'google_client_secret',
            'facebook_app_id', 'facebook_app_secret',
            'telegram_bot_username', 'telegram_bot_token'
        ];

        foreach ($socialSettings as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Handle Toggles
        Setting::set('google_login_enabled', $request->has('google_login_enabled') ? 'on' : 'off');
        Setting::set('facebook_login_enabled', $request->has('facebook_login_enabled') ? 'on' : 'off');
        Setting::set('telegram_login_enabled', $request->has('telegram_login_enabled') ? 'on' : 'off');
        
        // Handle About Image Upload
        if ($request->hasFile('about_image')) {
            $oldImage = Setting::get('about_image');
            if ($oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }

            $image = $request->file('about_image');
            $imageName = 'about_'.time().'.'.$image->extension();
            $image->move(public_path('uploads/settings'), $imageName);
            Setting::set('about_image', 'uploads/settings/'.$imageName);
        }

        Setting::clearCache();

        return redirect()->back()->with('success', 'University settings updated successfully!');
    }

    public function uploadImage(Request $request)
    {
        if (!Gate::allows('manage_settings')) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = 'content_'.time().'.'.$image->extension();
            $image->move(public_path('uploads/settings'), $imageName);
            
            return response()->json([
                'url' => asset('uploads/settings/'.$imageName)
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
