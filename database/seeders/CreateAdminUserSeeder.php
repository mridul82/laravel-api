<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Admin role exists
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            // Create Admin Role
            $adminRole = Role::create(['name' => 'Admin']);

            // Get all permissions
            $permissions = Permission::pluck('id', 'id')->all();

            // Assign all permissions to the admin role
            $adminRole->syncPermissions($permissions);
        }

        // Check if User role exists
        $userRole = Role::where('name', 'User')->first();
        if (!$userRole) {
            // Create User Role
            $userRole = Role::create(['name' => 'User']);

            // Assign basic permissions to the user role
            $userRole->syncPermissions([
                'lead-list',
                'lead-create',
                'contact-list',
                'contact-create'
            ]);
        }

        // Check if admin user exists
        $adminUser = User::where('email', 'admin@example.com')->first();
        if (!$adminUser) {
            // Create admin user
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]);

            // Assign admin role to the admin user
            $adminUser->assignRole([$adminRole->id]);
        }

        // Check if regular user exists
        $regularUser = User::where('email', 'user@example.com')->first();
        if (!$regularUser) {
            // Create regular user
            $regularUser = User::create([
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]);

            // Assign user role to the regular user
            $regularUser->assignRole([$userRole->id]);
        }
    }
}
