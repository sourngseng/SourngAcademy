<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Us',
                'title_km' => 'អំពីយើង',
                'content' => '<h2>Welcome to Sourng Academy</h2>
<p>Sourng Academy is a premier institution dedicated to excellence in education, research, and innovation. We empower students to achieve their full potential through world-class academic programs and adequate learning resources.</p>

<h3>Our Vision</h3>
<p>To be a leading university in the region, recognized for academic excellence and contribution to societal development.</p>

<h3>Our Mission</h3>
<p>To provide high-quality education, foster research and innovation, and prepare students for successful leaders.</p>

<h3>Why Choose Us?</h3>
<ul>
<li>Experienced and qualified faculty</li>
<li>Modern facilities and laboratories</li>
<li>Strong industry partnerships</li>
<li>Vibrant campus life</li>
<li>Comprehensive student support services</li>
</ul>',
                'content_km' => '<h2>សូមស្វាគមន៍មកកាន់សាលា Sourng Academy</h2>
<p>Sourng Academy គឺជាស្ថាប័នឈានមុខគេដែលឧទ្ទិសដល់ឧត្តមភាពក្នុងការអប់រំ ការស្រាវជ្រាវ និងការច្នៃប្រឌិត។</p>

<h3>ចក្ខុវិស័យ</h3>
<p>ក្លាយជាសាកលវិទ្យាល័យឈានមុខគេក្នុងតំបន់ ទទួលស្គាល់សម្រាប់ឧត្តមភាពសិក្សា និងការចូលរួមចំណែកក្នុងការអភិវឌ្ឍសង្គម។</p>',
                'meta_title' => 'About Us - Sourng Academy',
                'meta_description' => 'Learn about Sourng Academy, our vision, mission, and commitment to educational excellence.',
                'icon' => 'fas fa-info-circle',
                'show_in_footer' => true,
                'show_in_header' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'title_km' => 'ទំនាក់ទំនងយើង',
                'content' => '<h2>Get in Touch</h2>
<p>We are here to answer any questions you may have about our programs, admissions, or campus life.</p>

<h3>Contact Information</h3>
<div class="contact-info">
<p><strong><i class="fas fa-map-marker-alt"></i> Address:</strong><br>
Russian Federation Blvd, Phnom Penh, Cambodia</p>

<p><strong><i class="fas fa-phone"></i> Phone:</strong><br>
+855 12 345 678</p>

<p><strong><i class="fas fa-envelope"></i> Email:</strong><br>
info@sourng.edu<br>
admissions@sourng.edu</p>

<p><strong><i class="fas fa-clock"></i> Office Hours:</strong><br>
Mon-Fri: 8:00 AM - 5:00 PM<br>
Sat: 8:00 AM - 12:00 PM</p>
</div>',
                'content_km' => '<h2>ទាក់ទងមកយើង</h2>
<p>យើងនៅទីនេះដើម្បីឆ្លើយសំណួរដែលអ្នកមានអំពីកម្មវិធី ការចុះឈ្មោះ ឬជីវិតក្នុងបរិវេណសាលា។</p>

<h3>ព័ត៌មានទំនាក់ទំនង</h3>
<p><strong>អាសយដ្ឋាន:</strong> មហាវិថីសហព័ន្ធរុស្ស៊ី រាជធានីភ្នំពេញ កម្ពុជា</p>
<p><strong>ទូរស័ព្pទ:</strong> +៨៥៥ ១២ ៣៤៥ ៦៧៨</p>
<p><strong>អ៊ីមែល:</strong> info@sourng.edu</p>',
                'meta_title' => 'Contact Us - Sourng Academy',
                'meta_description' => 'Contact Sourng Academy for admissions, inquiries, or assistance.',
                'icon' => 'fas fa-envelope',
                'show_in_footer' => true,
                'show_in_header' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'admissions',
                'title' => 'Admissions',
                'title_km' => 'ការចុះឈ្មោះ',
                'content' => '<h2>Admission Information</h2>
<p>Apply to Sourng Academy and start your journey towards a successful career.</p>

<h3>Requirements</h3>
<ul>
<li>High School Diploma or equivalent</li>
<li>Pass the entrance examination</li>
<li>English proficiency test (if applicable)</li>
</ul>

<h3>How to Apply</h3>
<ol>
<li>Fill out the online application form</li>
<li>Submit required documents</li>
<li>Pay the application fee</li>
<li>Take the entrance exam</li>
</ol>',
                'content_km' => '<h2>ព័ត៌មានការចុះឈ្មោះ</h2>
<p>ដាក់ពាក្យចូលរៀននៅ Sourng Academy និងចាប់ផ្តើមដំណើរឆ្ពោះទៅរកអាជីពជោគជ័យ។</p>

<h3>តម្រូវការ</h3>
<ul>
<li>សញ្ញាបត្រមធ្យមសិក្សាទុតិយភូមិ ឬសមមូល</li>
<li>ជាប់ការប្រឡងចូល</li>
</ul>',
                'meta_title' => 'Admissions - Sourng Academy',
                'meta_description' => 'Information about admission requirements and application process.',
                'icon' => 'fas fa-user-graduate',
                'show_in_footer' => true,
                'show_in_header' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($pages as $page) {
            // Convert title and content to JSON format
            $title = json_encode([
                'en' => $page['title'],
                'km' => $page['title_km'] ?? $page['title']
            ], JSON_UNESCAPED_UNICODE);
            
            $content = json_encode([
                'en' => $page['content'],
                'km' => $page['content_km'] ?? $page['content']
            ], JSON_UNESCAPED_UNICODE);
            
            // Remove helper keys and add JSON versions
            unset($page['title_km'], $page['content_km']);
            $page['title'] = $title;
            $page['content'] = $content;
            
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
