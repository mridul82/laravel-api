<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-admin-role {email? : The email of the user to assign the Admin role to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the Admin role with all permissions to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get or create the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Ensure all permissions exist
        $this->ensureAllPermissionsExist();

        // Assign all permissions to the Admin role
        $adminRole->syncPermissions(Permission::all());

        // Get the email from the command argument
        $email = $this->argument('email');

        if ($email) {
            // Assign Admin role to the specified user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("User with email {$email} not found.");
                return 1;
            }

            $user->syncRoles(['Admin']);
            $this->info("Admin role with all permissions assigned to user {$email}.");
        } else {
            // Ask the user to select from a list of users
            $users = User::all();

            if ($users->isEmpty()) {
                $this->error('No users found in the database.');
                return 1;
            }

            $userChoices = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => "{$user->name} ({$user->email})"
                ];
            })->pluck('name', 'id')->toArray();

            $userId = $this->choice(
                'Select a user to assign the Admin role:',
                $userChoices
            );

            // Get the selected user ID
            $userId = array_search($userId, $userChoices);
            $user = User::find($userId);

            $user->syncRoles(['Admin']);
            $this->info("Admin role with all permissions assigned to user {$user->email}.");
        }

        return 0;
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

        $this->info('All required permissions have been created.');
    }
}
