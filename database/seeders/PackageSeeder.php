<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            [
                "name" => "User Starter",
                "price_per_month" => 0,
                "max_per_day" => 5,
            ],
            [
                "name" => "User Pro",
                "price_per_month" => 10,
                "max_per_day" => 20,
            ],
            [
                "name" => "User Premium",
                "price_per_month" => 20,
                "max_per_day" => 40,
            ]
        ];

        Package::insert($packages);
    }
}
