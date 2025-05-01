<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Get all permissions
        $allPermissions = Permission::all();

        // Make sure all permissions exist
        $this->ensureAllPermissionsExist();

        // Assign all permissions to Admin role
        $adminRole->syncPermissions(Permission::all());

        // Update all users with Admin role to have all permissions
        $adminUsers = User::role('Admin')->get();
        foreach ($adminUsers as $user) {
            // This will ensure the user has the Admin role with all permissions
            $user->syncRoles(['Admin']);
        }

        $this->command->info('All Admin users have been updated with all permissions.');
    }

    /**
     * Ensure all required permissions exist in the database
     */
    private function ensureAllPermissionsExist(): void
    {
        $requiredPermissions = [
            // User permissions
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role permissions
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Lead permissions
            'lead-list',
            'lead-create',
            'lead-edit',
            'lead-delete',

            // Contact permissions
            'contact-list',
            'contact-create',
            'contact-edit',
            'contact-delete',

            // Product permissions
            'product-list',
            'product-create',
            'product-edit',
            'product-delete'
        ];

        foreach ($requiredPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
