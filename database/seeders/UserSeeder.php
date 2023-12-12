<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            "name" => "Super Admin",
            "email" => "superadmin@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $superAdmin->assignRole("Super Admin");

        $admin = User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $admin->assignRole("Admin");

        $user = User::create([
            "name" => "Christianus Yoga Wibisono",
            "email" => "christianuswibisono@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $user->assignRole("User Starter");
    }
}
