<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting University ERP database seeding...');
        
        // 1. Roles and Permissions
        $this->command->info('📋 Seeding Roles & Permissions...');
        $this->call(UniversityRoleSeeder::class);
        
        // 2. Settings
        $this->command->info('⚙️  Seeding System Settings...');
        $this->call(UniversitySettingSeeder::class);
        
        // 3. Demo Data (Campuses, Users, Academic Structure)
        $this->command->info('🏫 Seeding University Demo Data...');
        $this->call(UniversityDemoSeeder::class);
        
        // 4. Content (Pages, Blog, Services, Slides)
        $this->command->info('📄 Seeding Pages & Content...');
        $this->call([
            PageSeeder::class,
            ContentSeeder::class,
        ]);
        
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Super Admin: admin@sourng.edu / password');
        $this->command->info('   Teacher: teacher@sourng.edu / password');
        $this->command->info('   Student: student@sourng.edu / password');
        $this->command->newLine();
    }
}
