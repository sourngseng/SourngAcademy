<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversityDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Main Campus
        $campus = \App\Modules\Core\Models\Campus::create([
            'name_en' => 'Main Campus - Phnom Penh',
            'name_km' => 'សាខារាជធានីភ្នំពេញ',
            'code' => 'PP-01',
            'short_name' => 'PP Campus',
            'address' => 'Russian Blvd, Phnom Penh, Cambodia',
            'phone' => '012345678',
            'email' => 'info@sourng.edu',
            'latitude' => 11.5564,
            'longitude' => 104.9282,
            'is_active' => true,
        ]);
        
        // Add sample image
        \App\Modules\Core\Models\CampusImage::create([
             'campus_id' => $campus->id,
             'image_path' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
             'is_thumbnail' => true,
             'order' => 1
        ]);
        
        $branch = \App\Modules\Core\Models\Campus::create([
            'name_en' => 'Battambang Campus',
            'name_km' => 'សាខាបាត់ដំបង',
            'code' => 'BTB-01',
            'short_name' => 'BTB Campus',
            'address' => 'National Road 5, Battambang',
            'phone' => '098765432',
            'email' => 'btb@sourng.edu',
            'is_active' => true,
        ]);

        // 2. Academic Structure (Faculties -> Departments -> Programs)
        $facultySc = \DB::table('faculties')->insertGetId([
            'campus_id' => $campus->id,
            'name' => 'Faculty of Science & Engineering',
            'code' => 'FOSE',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $deptCS = \DB::table('departments')->insertGetId([
            'faculty_id' => $facultySc,
            'name' => 'Computer Science',
            'code' => 'CS',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $progIT = \DB::table('programs')->insertGetId([
            'department_id' => $deptCS,
            'name' => 'Bachelor of Information Technology',
            'code' => 'BIT',
            // 'degree_level' => 'Bachelor', // Column likely 'level' or similar, check schema if needed but error said 'degree_level' unknown. 
            // Checking Program model might be better, but assuming user request context is limited. 
            // The error said "Unknown column 'degree_level'". 
            // Let's check the migration for programs if possible, but I can't in this turn.
            // I'll comment out the unknown columns to proceed with seeding what works.
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 3. Academic Calendar
        $yearId = \DB::table('academic_years')->insertGetId([
            'campus_id' => $campus->id,
            'name' => '2025-2026',
            'start_date' => '2025-10-01',
            'end_date' => '2026-08-31',
            'status' => true, // Corrected from is_current
            'created_at' => now(), 'updated_at' => now()
        ]);

        $semId = \DB::table('semesters')->insertGetId([
            'academic_year_id' => $yearId,
            'name' => 'Semester 1',
            'start_date' => '2025-10-15',
            'end_date' => '2026-02-15',
            'is_active' => true,
            'created_at' => now(), 'updated_at' => now()
        ]);

        // Create Super Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@sourng.edu'],
            [
                'name' => 'Super Admin',
                'password' => \Hash::make('password'),
                'role' => 'super_admin',
                'status' => 'active',
                'username' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 4. Create Users (Teachers & Student)
        // Teachers
        $teachersData = [
            ['name' => 'Mr. Sok Dara', 'email' => 'teacher@sourng.edu', 'password' => 'password'],
            ['name' => 'Ms. Keo Malis', 'email' => 'malis@sourng.edu', 'password' => 'password'],
            ['name' => 'Mr. Ly Visal', 'email' => 'visal@sourng.edu', 'password' => 'password'],
            ['name' => 'Dr. Chan Sopheak', 'email' => 'sopheak@sourng.edu', 'password' => 'password'],
            ['name' => 'Ms. Lim Bopharath', 'email' => 'bopharath@sourng.edu', 'password' => 'password'],
        ];

        $mentorIds = [];
        foreach ($teachersData as $tData) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => \Hash::make($tData['password']),
                    'role' => 'teacher',
                    'campus_id' => $campus->id, // Default to main
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $mentorIds[] = $user->id;
        }
        $teacher = \App\Models\User::find($mentorIds[0]); // Keep reference for demo class

        // Student
        $student = \App\Models\User::firstOrCreate(
            ['email' => 'student@sourng.edu'],
            [
                'name' => 'Chan Vithu',
                'password' => \Hash::make('password'),
                'role' => 'student',
                'campus_id' => $campus->id,
                'status' => 'active',
                'username' => 'STU-001',
                'email_verified_at' => now(),
            ]
        );

        // 5. Subjects & Classes
        // Degree Levels (assuming table exists, if not, skip)
        if (\Schema::hasTable('degree_levels')) {
             $bachelorLevel = \DB::table('degree_levels')->where('name', 'Bachelor')->first();
             if(!$bachelorLevel) {
                 \DB::table('degree_levels')->insert(['name' => 'Bachelor', 'created_at' => now(), 'updated_at' => now()]);
             }
             $masterLevel = \DB::table('degree_levels')->where('name', 'Master')->first();
             if(!$masterLevel) {
                 \DB::table('degree_levels')->insert(['name' => 'Master', 'created_at' => now(), 'updated_at' => now()]);
             }
        }

        // Subjects
        $subjectsData = [
            ['name' => 'Introduction to Programming', 'code' => 'CS101'],
            ['name' => 'Database Management Systems', 'code' => 'CS102'],
            ['name' => 'Web Development I', 'code' => 'CS103'],
            ['name' => 'Data Structures & Algorithms', 'code' => 'CS201'],
            ['name' => 'Computer Networks', 'code' => 'CS202'],
        ];

        $subjectIds = [];
        foreach ($subjectsData as $sData) {
            $subjectIds[] = \DB::table('subjects')->insertGetId([
                'program_id' => $progIT,
                'name' => $sData['name'],
                'code' => $sData['code'],
                'credit' => 3, 
                'type' => 'Core', 
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
        $subjectId = $subjectIds[0];

        // Classrooms (10 for 2 campuses)
        $campusesList = [$campus->id, $branch->id];
        // Match migration enum: ['Lecture Hall', 'Lab', 'Classroom', 'Online']
        $roomTypes = ['Lecture Hall', 'Lab', 'Classroom'];
        
        for ($i = 1; $i <= 5; $i++) {
            // Main Campus
            if (!\App\Modules\Academic\Models\Classroom::where('room_number', 'A-' . (100 + $i))->where('campus_id', $campus->id)->exists()) {
                \App\Modules\Academic\Models\Classroom::create([
                    'room_number' => 'A-' . (100 + $i),
                    'campus_id' => $campus->id,
                    'building' => 'Building A',
                    'capacity' => 40,
                    'type' => $roomTypes[array_rand($roomTypes)],
                    'description' => 'Main Campus Room',
                    'status' => true
                ]);
            }

            // Branch Campus
            if (!\App\Modules\Academic\Models\Classroom::where('room_number', 'B-' . (100 + $i))->where('campus_id', $branch->id)->exists()) {
                \App\Modules\Academic\Models\Classroom::create([
                    'room_number' => 'B-' . (100 + $i),
                    'campus_id' => $branch->id,
                    'building' => 'Building B',
                    'capacity' => 30,
                    'type' => $roomTypes[array_rand($roomTypes)],
                    'description' => 'Battambang Campus Room',
                    'status' => true
                ]);
            }
        }

        $classId = \DB::table('classes')->insertGetId([
            'campus_id' => $campus->id, 
            'semester_id' => $semId,    
            'name' => 'CS101 Batch A',
            'code' => 'CS101-A-2025', 
            'status' => 'Ongoing', 
            'created_at' => now(), 'updated_at' => now()
        ]);

        \DB::table('class_subjects')->insert([
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacher->id,
            'schedule_json' => json_encode(['Mon' => '08:00-11:00']),
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 6. Enrollments
        \DB::table('enrollments')->insert([
            'student_id' => $student->id,
            'class_id' => $classId,
            'enrolled_at' => now(),
            'status' => 'Enrolled', // Corrected from 'active'
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 7. Finance Data
        $feeId = \DB::table('fee_structures')->insertGetId([
            'program_id' => $progIT,
            'academic_year_id' => $yearId,
            'semester_id' => $semId,
            'name' => 'Semester 1 Tuition Fee',
            'amount' => 600.00,
            'type' => 'Tuition',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // Create Invoice
        $invoiceId = \DB::table('invoices')->insertGetId([
            'campus_id' => $campus->id,
            'student_id' => $student->id,
            'invoice_number' => 'INV-2025-001',
            'total_amount' => 600.00,
            'paid_amount' => 0.00,
            'due_date' => '2025-10-30',
            'status' => 'Unpaid',
            'created_at' => now(), 'updated_at' => now()
        ]);
        
        \DB::table('invoice_items')->insert([
            'invoice_id' => $invoiceId,
            'fee_structure_id' => $feeId,
            'description' => 'Tuition Fee for CS101',
            'amount' => 600.00,
            'created_at' => now(), 'updated_at' => now()
        ]);
        
        $this->command->info('University Demo Data Seeded Successfully!');
    }
}
