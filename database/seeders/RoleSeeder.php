<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::create([
            'name' => 'super-admin',
            'title' => '超级管理员',
        ]);

        $role->permissions()->attach(Permission::pluck('id'));
    }
}
