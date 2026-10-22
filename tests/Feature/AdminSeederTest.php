<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_super_admin_when_admin_table_is_empty(): void
    {
        $this->seed(AdminSeeder::class);

        $this->assertDatabaseCount('admin_users', 1);
        $admin = DB::table('admin_users')->first();
        $this->assertNotNull($admin);
        $this->assertSame(1, (int) $admin->is_super);
        $this->assertSame(1, (int) $admin->status);
        $this->assertMatchesRegularExpression('/^\$2[aby]\$/', (string) $admin->password);
    }

    public function test_skips_when_admin_already_exists(): void
    {
        DB::table('admin_users')->insert([
            'username' => 'existing',
            'password' => Hash::make('secret-123'),
            'status' => 1,
            'is_super' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->seed(AdminSeeder::class);

        $this->assertDatabaseCount('admin_users', 1);
        $this->assertDatabaseHas('admin_users', ['username' => 'existing']);
    }
}
