<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
                ->count(20)
                ->create();
        $user_list = Permission::create(['name'=> 'users.list']);
        $user_view = Permission::create(['name'=> 'users.view']);
        $user_create = Permission::create(['name'=> 'users.create']);
        $user_update = Permission::create(['name'=> 'users.update']);
        $user_delete = Permission::create(['name'=> 'users.delete']);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('admin');
        $admin->givePermissionTo([$user_list,$user_create,$user_update,$user_delete,$user_view]);




        /* create user */
        $user = User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        /* create role and give this role some permission */
        /* asign this user for this role */
        $user->assignRole('user');
        /* give permission for this user remote this role */ // can be any permission without need role
        $user->givePermissionTo([$user_list]);// table model has permissions
    }
}
