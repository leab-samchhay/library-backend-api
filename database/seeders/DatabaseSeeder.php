<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ដំណើរការ PermissionSeeder ដើម្បីបង្កើត Permissions ទាំងអស់
        // ព្រមទាំង Assign សិទ្ធិពេញលេញឲ្យ Admin និងសិទ្ធិចូលមើលគ្រប់ function ឲ្យ User
        $this->call([
            PermissionSeeder::class,
        ]);
    }
}
