<?php

namespace Database\Seeders;

use App\Support\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 超级管理员：通过 Gate::before 全量放行，无需显式授权
        Role::findOrCreate('super_admin', 'web');

        // 管理员：全部权限
        Role::findOrCreate('admin', 'web')
            ->syncPermissions(Permissions::names());

        // 编辑：内容管理全部 + 后台访问
        Role::findOrCreate('editor', 'web')
            ->syncPermissions([
                Permissions::ACCESS_ADMIN,
                ...Permissions::forModel('article'),
                ...Permissions::forModel('page'),
                ...Permissions::forModel('category'),
                ...Permissions::forModel('tag'),
                ...Permissions::forModel('comment'),
                ...Permissions::forModel('media'),
                ...Permissions::forModel('media_category'),
            ]);

        // 运营：运营工具全部 + 内容只读 + 后台访问
        Role::findOrCreate('operator', 'web')
            ->syncPermissions([
                Permissions::ACCESS_ADMIN,
                ...Permissions::forModel('banner'),
                ...Permissions::forModel('announcement'),
                ...Permissions::forModel('link'),
                ...Permissions::forModel('article', ['view_any']),
                ...Permissions::forModel('page', ['view_any']),
            ]);

        // 客服：评论全部 + 文章/页面只读 + 后台访问
        Role::findOrCreate('customer_service', 'web')
            ->syncPermissions([
                Permissions::ACCESS_ADMIN,
                ...Permissions::forModel('comment'),
                ...Permissions::forModel('article', ['view_any']),
                ...Permissions::forModel('page', ['view_any']),
            ]);

        // 审计：所有模块只读 + 后台访问
        $auditorPermissions = [Permissions::ACCESS_ADMIN];
        foreach (array_keys(Permissions::all()) as $model) {
            $auditorPermissions = array_merge($auditorPermissions, Permissions::forModel($model, ['view_any']));
        }
        $auditorPermissions[] = 'operation_log.view_any';
        Role::findOrCreate('auditor', 'web')
            ->syncPermissions($auditorPermissions);

        // 前台会员
        Role::findOrCreate('member', 'web');
    }
}
