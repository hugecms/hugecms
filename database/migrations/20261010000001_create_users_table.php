<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateUsersTable extends Migrator
{
    /**
     * 前台用户表 + 后台管理员账户与经典 RBAC（角色、权限、角色-权限、管理员-角色）
     */
    public function change()
    {
        // 前台用户账户表：仅存放登录凭证与账户状态，资料信息见 user_profiles 表
        $users = $this->table('users', ['comment' => '用户账户表']);
        $users->addColumn(Column::string('username', 50)->setNull(false)->setComment('用户名'))
            ->addColumn(Column::string('email', 100)->setNullable()->setComment('邮箱'))
            ->addColumn(Column::string('phone', 20)->setNullable()->setComment('手机号'))
            ->addColumn(Column::string('password', 255)->setNull(false)->setComment('密码哈希'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=正常'))
            ->addColumn(Column::dateTime('last_login_time')->setNullable()->setComment('最后登录时间'))
            ->addColumn(Column::string('last_login_ip', 45)->setNullable()->setComment('最后登录IP'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('username', ['unique' => true, 'name' => 'uk_users_username'])
            ->addIndex('email', ['unique' => true, 'name' => 'uk_users_email'])
            ->addIndex('phone', ['unique' => true, 'name' => 'uk_users_phone'])
            ->create();

        // 后台管理员账户表：结构与 users 一致，前后台账户隔离
        $adminUsers = $this->table('admin_users', ['comment' => '后台管理员账户表']);
        $adminUsers->addColumn(Column::string('username', 50)->setNull(false)->setComment('用户名'))
            ->addColumn(Column::string('email', 100)->setNullable()->setComment('邮箱'))
            ->addColumn(Column::string('phone', 20)->setNullable()->setComment('手机号'))
            ->addColumn(Column::string('password', 255)->setNull(false)->setComment('密码哈希'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=正常'))
            ->addColumn(Column::dateTime('last_login_time')->setNullable()->setComment('最后登录时间'))
            ->addColumn(Column::string('last_login_ip', 45)->setNullable()->setComment('最后登录IP'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('username', ['unique' => true, 'name' => 'uk_admin_users_username'])
            ->addIndex('email', ['unique' => true, 'name' => 'uk_admin_users_email'])
            ->addIndex('phone', ['unique' => true, 'name' => 'uk_admin_users_phone'])
            ->create();

        // 角色表
        $roles = $this->table('admin_roles', ['comment' => '后台角色表']);
        $roles->addColumn(Column::string('name', 50)->setNull(false)->setComment('角色名称'))
            ->addColumn(Column::string('code', 50)->setNull(false)->setComment('角色标识'))
            ->addColumn(Column::string('description', 255)->setNullable()->setComment('角色描述'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('code', ['unique' => true, 'name' => 'uk_admin_roles_code'])
            ->create();

        // 权限表：树形结构，菜单与按钮统一管理
        $permissions = $this->table('admin_permissions', ['comment' => '后台权限表']);
        $permissions->addColumn(Column::unsignedInteger('parent_id')->setNull(false)->setDefault(0)->setComment('父级ID,0=顶级'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('权限名称'))
            ->addColumn(Column::string('code', 100)->setNull(false)->setComment('权限标识,如 user:list'))
            ->addColumn(Column::tinyInteger('type')->setNull(false)->setDefault(1)->setComment('类型:1=菜单,2=按钮'))
            ->addColumn(Column::string('path', 100)->setNullable()->setComment('路由地址'))
            ->addColumn(Column::string('icon', 100)->setNullable()->setComment('菜单图标'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('code', ['unique' => true, 'name' => 'uk_admin_permissions_code'])
            ->addIndex('parent_id', ['name' => 'idx_admin_permissions_parent_id'])
            ->create();

        // 角色-权限关联表
        $rolePermissions = $this->table('admin_role_permissions', ['id' => false, 'primary_key' => ['role_id', 'permission_id'], 'comment' => '角色权限关联表']);
        $rolePermissions->addColumn(Column::unsignedInteger('role_id')->setNull(false)->setComment('角色ID'))
            ->addColumn(Column::unsignedInteger('permission_id')->setNull(false)->setComment('权限ID'))
            ->addTimestamps('create_time', null)
            ->addIndex('permission_id', ['name' => 'idx_admin_role_permissions_permission_id'])
            ->create();

        // 管理员-角色关联表
        $userRoles = $this->table('admin_user_roles', ['id' => false, 'primary_key' => ['admin_user_id', 'role_id'], 'comment' => '管理员角色关联表']);
        $userRoles->addColumn(Column::unsignedInteger('admin_user_id')->setNull(false)->setComment('管理员ID'))
            ->addColumn(Column::unsignedInteger('role_id')->setNull(false)->setComment('角色ID'))
            ->addTimestamps('create_time', null)
            ->addIndex('role_id', ['name' => 'idx_admin_user_roles_role_id'])
            ->create();
    }
}
