<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Clear the permissions cache
app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

echo "Permissions cache cleared.\n\n";

// Get the admin user
$admin = \App\Models\User::where('email', 'admin@example.com')->first();

if (!$admin) {
    echo "Admin user not found!\n";
    exit(1);
}

echo "Admin user found: {$admin->name} ({$admin->email})\n";

// Check if the user has the Admin role
$hasAdminRole = $admin->hasRole('Admin');
echo "Has Admin role: " . ($hasAdminRole ? 'Yes' : 'No') . "\n";

// Check if the user has the user-list permission
$hasUserListPermission = $admin->hasPermissionTo('user-list');
echo "Has user-list permission: " . ($hasUserListPermission ? 'Yes' : 'No') . "\n";

// Get all roles
$roles = $admin->roles()->get();
echo "\nAssigned roles:\n";
foreach ($roles as $role) {
    echo "- {$role->name}\n";
}

// Get all permissions
$permissions = $admin->getAllPermissions();
echo "\nAll permissions:\n";
foreach ($permissions as $permission) {
    echo "- {$permission->name}\n";
}

// If the user doesn't have the Admin role or user-list permission, assign them
if (!$hasAdminRole || !$hasUserListPermission) {
    echo "\nFixing permissions...\n";
    
    // Get the Admin role
    $adminRole = \Spatie\Permission\Models\Role::where('name', 'Admin')->first();
    
    if (!$adminRole) {
        echo "Admin role not found in the database!\n";
        exit(1);
    }
    
    // Make sure the Admin role has all permissions
    $allPermissions = \Spatie\Permission\Models\Permission::all();
    $adminRole->syncPermissions($allPermissions);
    
    // Assign the Admin role to the user
    $admin->syncRoles([$adminRole]);
    
    echo "Admin role with all permissions assigned to {$admin->email}.\n";
    
    // Verify the changes
    $admin->refresh();
    echo "Has Admin role after fix: " . ($admin->hasRole('Admin') ? 'Yes' : 'No') . "\n";
    echo "Has user-list permission after fix: " . ($admin->hasPermissionTo('user-list') ? 'Yes' : 'No') . "\n";
}

echo "\nDone.\n";
