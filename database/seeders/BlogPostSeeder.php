<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a user
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@sourng.edu',
                'role' => 'super_admin',
            ]);
        }

        // Ensure we have categories
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
            // Use updateOrInsert to avoid unique constraint violation if exists
            $existing = DB::table('blog_categories')->where('slug', $slug)->first();
            
            if ($existing) {
                $id = $existing->id;
            } else {
                $id = DB::table('blog_categories')->insertGetId([
                    'name' => $category,
                    'slug' => $slug,
                    'description' => 'Posts related to ' . $category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $catIds[] = $id;
        }

        // Create Blog Posts
        for ($i = 1; $i <= 10; $i++) {
            $title = "University Update #$i: " . fake()->sentence(3);
            $slug = Str::slug($title);
            
            // Check existence
            if (DB::table('blog_posts')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('blog_posts')->insert([
                'user_id' => $user->id,
                'category_id' => $catIds[array_rand($catIds)],
                'title' => $title,
                'slug' => $slug,
                'excerpt' => fake()->paragraph(2),
                'content' => '<p>' . fake()->paragraph(5) . '</p><h3>Key Highlights</h3><ul><li>' . fake()->sentence() . '</li><li>' . fake()->sentence() . '</li></ul><p>' . fake()->paragraph(3) . '</p>',
                'image_path' => null, // Or 'https://source.unsplash.com/random/800x600?university' but unsplash source is deprecated often
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 30)),
                'views_count' => rand(10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('Blog Posts seeded successfully!');
    }
}
