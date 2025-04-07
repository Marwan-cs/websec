<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create or ensure permissions exist (added view_profile and edit_profile if needed)
        $permissions = [
            'view_users',
            'edit_users',
            'delete_users',
            'create_users',
            'view_products',
            'edit_products',
            'delete_products',
            'create_products',
            'manage_roles',
            'view_reports',
            'export_data',
            'view_dashboard',
            'manage_stock',
            'view_profile',   // Added permission if required
            'edit_profile',   // Added permission if required
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Get or create admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Find or create admin user
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin/2000'), // Better to use env('ADMIN_PASSWORD')
            ]);
        }
        
        // Assign admin role to the user
        $admin->assignRole('admin');
    }
}
