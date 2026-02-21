<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class UniversityRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_admin' => 'Full system control',
            'campus_admin' => 'Control only assigned campus',
            'staff' => 'Assigned permissions by admin',
            'teacher' => 'Manage assigned subjects and classes',
            'student' => 'View classes, scores, and attendance',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // Basic Permissions
        $permissions = [
            'manage_campuses',
            'manage_users',
            'view_reports',
            'manage_feestructures',
            'manage_classes',
            'take_attendance',
            'view_own_records',
            'manage_settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        
        // Assign permissions to roles (example)
        $superAdmin = Role::where('name', 'super_admin')->first();
        $campusAdmin = Role::where('name', 'campus_admin')->first();
        $teacher = Role::where('name', 'teacher')->first();

        // Attach permissions
        $superAdmin->permissions()->sync(Permission::all());
        $campusAdmin->permissions()->sync(Permission::whereIn('name', ['manage_users', 'view_reports', 'manage_classes'])->get());
        $teacher->permissions()->sync(Permission::whereIn('name', ['manage_classes', 'take_attendance'])->get());
    }
}
