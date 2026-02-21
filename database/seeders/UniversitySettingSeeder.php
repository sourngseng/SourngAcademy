<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class UniversitySettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'university_name', 'value' => 'Sourng Academy'],
            ['key' => 'university_email', 'value' => 'info@sourng.edu'],
            ['key' => 'university_phone', 'value' => '+855 12 345 678'],
            ['key' => 'university_address', 'value' => 'Russian Blvd, Phnom Penh, Cambodia'],
            
            // About Page
            ['key' => 'about_title', 'value' => 'About Sourng Academy'],
            ['key' => 'about_description', 'value' => 'Sourng Academy is a premier institution dedicated to excellence in education, research, and innovation. We empower students to achieve their full potential.'],
            
            // Contact Page
            ['key' => 'contact_details', 'value' => '<h4>Contact Us</h4><p>We are here to answer any questions you may have about our programs and admissions.</p><h5>Office Hours</h5><ul><li>Monday - Friday: 8:00 AM - 5:00 PM</li><li>Saturday: 8:00 AM - 12:00 PM</li></ul>'],
            ['key' => 'contact_map_iframe', 'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3908.770597651034!2d104.89069531533654!3d11.56829199178716!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109519fe4077d69%3A0x20138e822e434660!2sPhnom%20Penh!5e0!3m2!1sen!2skh!4v1620000000000!5m2!1sen!2skh" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/sourngacademy'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/sourngacademy'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/sourngacademy'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/school/sourngacademy'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/sourngacademy'],
            
            // System Settings
            ['key' => 'academic_year_current', 'value' => '2025-2026'],
            ['key' => 'semester_current', 'value' => 'Semester 1'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('University Settings seeded successfully!');
    }
}
