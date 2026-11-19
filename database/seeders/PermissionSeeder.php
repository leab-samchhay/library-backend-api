<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Dashboard' => 'dashboard',
            'Books' => 'books',
            'Book Copies' => 'book_copies',
            'Category' => 'category',
            'Authors' => 'authors',
            'Publishers' => 'publishers',
            'Author Books' => 'author_books',
            'Loans & Return' => 'loans_return',
            'Loans' => 'loans',
            'Loans Detail' => 'loans_detail',
            'Book Return' => 'book_return',
            'Users' => 'users',
            'Member' => 'member',
            'Fines' => 'fines',
            'Fine Payments' => 'fine_payments',
        ];

        $actions = ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete'];

        // ១. បង្កើត permissions ទាំងអស់ក្នុងតារាង permissions
        foreach ($modules as $moduleName => $moduleSlug) {
            foreach ($actions as $actionSlug => $actionName) {
                // Dashboard has limited actions (មានតែ view ប៉ុណ្ណោះ)
                if ($moduleSlug === 'dashboard' && $actionSlug !== 'view') continue;

                Permission::firstOrCreate(
                    ['slug' => "{$moduleSlug}.{$actionSlug}"],
                    ['name' => "{$actionName} {$moduleName}"]
                );
            }
        }

        // ២. បង្កើត Admin និងផ្ដល់សិទ្ធិពេញលេញទាំងអស់ (Full Permissions: view, create, edit, delete)
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'role' => 'Admin',
                'status' => 1,
                'password' => Hash::make('123'),
            ]
        );

        $allPermissions = Permission::all();
        $admin->permissions()->sync($allPermissions->pluck('id'));

        // ៣. បង្កើត User ធម្មតា និងផ្ដល់សិទ្ធិចូលមើលបានគ្រប់ function ទាំងអស់ (View Only Permissions: *.view)
        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'user',
                'role' => 'User',
                'status' => 1,
                'password' => Hash::make('123'),
            ]
        );

        $viewPermissions = Permission::where('slug', 'like', '%.view')->pluck('id');
        $user->permissions()->sync($viewPermissions);
    }
}
