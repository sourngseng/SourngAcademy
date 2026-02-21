<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\GuestProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->guestProfile ?? new GuestProfile();
        
        return view('guest.profile.edit', compact('user', 'profile'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'id_type' => 'nullable|in:passport,national_id,driver_license',
            'id_number' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Update user name
        $user->update(['name' => $request->name]);
        
        // Handle profile image
        if ($request->hasFile('profile_image')) {
            try {
                $file = $request->file('profile_image');
                
                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    @unlink(public_path($user->profile_image));
                }
                
                // Generate unique filename
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists - use uploads folder instead of storage to avoid symlink issues
                $uploadPath = public_path('uploads/profiles');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move uploaded file
                $file->move($uploadPath, $filename);
                
                // Update user's profile_image path
                $user->profile_image = 'uploads/profiles/' . $filename;
                $user->save();
                
                \Log::info('Profile image uploaded: ' . $user->profile_image);
            } catch (\Exception $e) {
                \Log::error('Profile image upload failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }
        
        // Update or create guest profile
        $profileData = $request->only([
            'phone', 'date_of_birth', 'gender', 'address', 'city', 'country',
            'nationality', 'id_type', 'id_number', 
            'emergency_contact_name', 'emergency_contact_phone'
        ]);
        
        if ($user->guestProfile) {
            $user->guestProfile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            GuestProfile::create($profileData);
        }
        
        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        Auth::user()->update([
            'password' => bcrypt($request->password)
        ]);
        
        return back()->with('success', 'Password updated successfully!');
    }
}
