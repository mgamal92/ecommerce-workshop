<?php

namespace Database\Seeders;

use App\Permissions\PermissionsList;
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
        Permission::create(['name' => PermissionsList::LIST_PRODUCTS, 'guard_name' => 'web']);
        Permission::create(['name' => PermissionsList::CREATE_PRODUCTS, 'guard_name' => 'api-user']);
        Permission::create(['name' => PermissionsList::UPDATE_PRODUCTS, 'guard_name' => 'api-user']);
        Permission::create(['name' => PermissionsList::DELETE_PRODUCTS, 'guard_name' => 'api-user']);
        Permission::create(['name' => PermissionsList::IMPORT_CSV_PRODUCTS, 'guard_name' => 'api-user']);

        Permission::create(['name' => PermissionsList::LIST_CATEGORIES, 'guard_name' => 'web']);
        Permission::create(['name' => PermissionsList::CREATE_CATEGORIES, 'guard_name' => 'api-user']);
        Permission::create(['name' => PermissionsList::UPDATE_CATEGORIES, 'guard_name' => 'api-user']);
        Permission::create(['name' => PermissionsList::DELETE_CATEGORIES, 'guard_name' => 'api-user']);

        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'api-user']);

        $superAdminRole->givePermissionTo([
            PermissionsList::LIST_PRODUCTS,
            PermissionsList::CREATE_PRODUCTS,
            PermissionsList::UPDATE_PRODUCTS,
            PermissionsList::DELETE_PRODUCTS,
            PermissionsList::IMPORT_CSV_PRODUCTS,
            PermissionsList::LIST_CATEGORIES,
            PermissionsList::CREATE_CATEGORIES,
            PermissionsList::UPDATE_CATEGORIES,
            PermissionsList::DELETE_CATEGORIES,
        ]);

    }
}
