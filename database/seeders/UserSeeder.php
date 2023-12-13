<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Transaction;
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

        // get package id based on role
        $package = Package::where("name", $user->roles->first()->name)->first();

        Transaction::create([
            "user_id" => $user->id,
            "package_id" => $package->id,
            "start_date" => now(),
        ]);
    }
}
