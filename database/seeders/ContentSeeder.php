<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Blog Categories
        $categories = [
            'Academic News',
            'Student Life',
            'Research Updates',
            'Campus Events',
            'Alumni Stories'
        ];

        $catIds = [];
        foreach ($categories as $category) {
            $slug = Str::slug($category);
            $id = DB::table('blog_categories')->insertGetId([
                'name' => $category,
                'slug' => $slug,
                'description' => 'Posts related to ' . $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $catIds[] = $id;
        }

        // Get Admin ID (generically)
        $adminId = DB::table('users')->where('role', 'super_admin')->value('id') ?? 1;

        // 2. Blog Posts
        for ($i = 1; $i <= 5; $i++) {
            $title = "University Update #$i";
            $slug = Str::slug($title);
            
            DB::table('blog_posts')->insert([
                'user_id' => $adminId,
                'category_id' => $catIds[array_rand($catIds)] ?? null,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => "Stay updated with the latest news from Sourng Academy.",
                'content' => "<p>Detailed update regarding university activities, academic achievements, and upcoming events. We are committed to excellence.</p>",
                'image_path' => null,
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 10)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Services
        $services = [
            ['name' => 'Modern Library', 'icon' => 'pi pi-book', 'desc' => 'Access to thousands of books, journals, and digital resources.'],
            ['name' => 'Career Center', 'icon' => 'pi pi-briefcase', 'desc' => 'Expert guidance on internships, job placements, and career planning.'],
            ['name' => 'Student Support', 'icon' => 'pi pi-heart', 'desc' => 'Counseling, health services, and academic advising.'],
            ['name' => 'Sports Complex', 'icon' => 'pi pi-star', 'desc' => 'State-of-the-art gym, sports fields, and recreation areas.'],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert([
                'name' => $service['name'],
                'description' => $service['desc'],
                'icon' => $service['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Hero Slides
        $slides = [
            ['title' => 'Shaping the Future', 'subtitle' => 'World-Class Education', 'desc' => 'Join a diverse community of scholars and innovators.', 'order' => 1],
            ['title' => 'Inspiring Innovation', 'subtitle' => 'Research & Discovery', 'desc' => 'State-of-the-art facilities and expert faculty.', 'order' => 2],
            ['title' => 'Campus Life', 'subtitle' => 'Vibrant Community', 'desc' => 'Experience a rich and engaging student life.', 'order' => 3],
        ];

        foreach ($slides as $slide) {
            // Check if description column exists (it was added in a later migration)
            $data = [
                'title' => $slide['title'],
                'subtitle' => $slide['subtitle'],
                'image_path' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80', // detailed demo image
                'link_url' => '/programs',
                'order' => $slide['order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Add description/button_text if columns exist (handled by DB default/null if not in array, but we want to seed them)
            // Assuming migration ran, we add them.
            $data['description'] = $slide['desc'];
            $data['button_text'] = 'Learn More';

            DB::table('slides')->insert($data);
        }
        
        $this->command->info('University Content seeded successfully!');
    }
}
