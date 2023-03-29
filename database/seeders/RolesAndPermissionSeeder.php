<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'list-products', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'show-products', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'create-products', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'update-products', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'delete-products', 'guard_name' => 'api-user']);

        Permission::create(['name' => 'list-categories', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'show-categories', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'create-categories', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'update-categories', 'guard_name' => 'api-user']);
        Permission::create(['name' => 'delete-categories', 'guard_name' => 'api-user']);


        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api-user']);

        $superAdminRole->givePermissionTo([
            'list-products',
            'show-products',
            'create-products',
            'update-products',
            'delete-products',
            'list-categories',
            'show-categories',
            'create-categories',
            'update-categories',
            'delete-categories',
        ]);
    }
}
