<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    use Spatie\Permission\Models\Permission;

    public function run() {
        
        $permissions = [
            'edit_users',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_stock',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}