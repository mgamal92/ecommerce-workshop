<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => fake()->name(),
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    
        $role = Role::create(['name' => 'admin']);
        $permissions = [
            'list-customers',
            'create-customer',
            'update-customer',
            'show-customer',
            'delete-customer',
            'list-staff_members',
            'create-staff_member',
            'update-staff_member',
            'show-staff_member',
            'delete-staff_member',
            'list-categories',
            'create-category',
            'update-category',
            'show-category',
            'delete-category',
            'list-products',
            'create-product',
            'update-product',
            'show-product',
            'delete-product',
            'list-orders',
            'show-order',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
            $role->givePermissionTo($permission);
            $user->givePermissionTo($permission);
        }   
        $roles = ['admin'];
        $user->syncRoles($roles);
        // $user_permissions_via_role = $user->getPermissionsViaRoles();
        // dd($user_permissions_via_role);
        // $user_permissions = $user->getDirectPermissions();
        // dd($user_permissions);
        // $all_roles_in_database = Role::all()->pluck('name');
        // dd($all_roles_in_database);
    }
}
