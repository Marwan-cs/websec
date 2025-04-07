<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $editUsersPermission = Permission::create(['name' => 'edit_users']);
        $viewUsersPermission = Permission::create(['name' => 'view_users']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($editUsersPermission);
        $adminRole->givePermissionTo($viewUsersPermission);
        $userRole->givePermissionTo($viewUsersPermission);
    }
}