<?php

namespace Database\Seeders;

use App\Models\MediaLibrary;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MediaLibrary::singleton();

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            MenuSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);
        $admin->assignRole('super_admin');

        if (app()->environment('local')) {
            $this->call([
                ContentSeeder::class,
            ]);
        }
    }
}
