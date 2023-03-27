<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            CustomerSeeder::class,
            CartSeeder::class,
        ]);

        //create super-admin, admin and editor roles
        Role::create(['name' => 'super-admin', 'guard_name' => 'api-user']);
        Role::create(['name' => 'admin', 'guard_name' => 'api-user']);
        Role::create(['name' => 'editor', 'guard_name' => 'api-user']);
    }
}
