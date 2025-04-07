<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (including the missing ones)
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
            'view_profile',    // Added
            'edit_profile',    // Added
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo(['view_profile', 'edit_profile']);

        $employeeRole = Role::create(['name' => 'employee', 'guard_name' => 'web']);
        $employeeRole->givePermissionTo([
            'edit_profile', 
            'view_profile', 
            'view_users', 
            'edit_users', 
            'view_products', 
            'edit_products', 
            'delete_products', 
            'create_products', 
            'manage_stock'
        ]);
    }
}
