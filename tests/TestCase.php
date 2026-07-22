<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    /**
     * 创建一个拥有 super_admin 角色的用户（Gate::before 全量放行）。
     */
    protected function createSuperAdmin(): User
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::findOrCreate('super_admin', 'web');

        $user = User::factory()->create();
        $user->assignRole('super_admin');

        return $user;
    }
}
