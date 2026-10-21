<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 后台管理域：管理员账户 + 经典 RBAC（角色、权限、角色-权限、管理员-角色）
     */
    public function up(): void
    {
        // 后台管理员账户表：结构与 users 一致，前后台账户隔离
        Schema::create('admin_users', function (Blueprint $table) {
            $table->comment('后台管理员账户表');
            $table->id();
            $table->string('username', 50)->comment('用户名');
            $table->string('email', 100)->nullable()->comment('邮箱');
            $table->string('phone', 20)->nullable()->comment('手机号');
            $table->string('password', 255)->comment('密码哈希');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=正常');
            $table->tinyInteger('is_super')->default(0)->comment('超级管理员:0=否,1=是;超管不受角色权限限制');
            $table->dateTime('last_login_time')->nullable()->comment('最后登录时间');
            $table->string('last_login_ip', 45)->nullable()->comment('最后登录IP');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('username', 'uk_admin_users_username');
            $table->unique('email', 'uk_admin_users_email');
            $table->unique('phone', 'uk_admin_users_phone');
        });

        // 角色表
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->comment('后台角色表');
            $table->id();
            $table->string('name', 50)->comment('角色名称');
            $table->string('code', 50)->comment('角色标识');
            $table->string('description', 255)->nullable()->comment('角色描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('code', 'uk_admin_roles_code');
        });

        // 权限表：树形结构，菜单与按钮统一管理
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->comment('后台权限表');
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID,0=顶级');
            $table->string('name', 50)->comment('权限名称');
            $table->string('code', 100)->comment('权限标识,如 user:list');
            $table->tinyInteger('type')->default(1)->comment('类型:1=菜单,2=按钮');
            $table->string('path', 100)->nullable()->comment('路由地址');
            $table->string('icon', 100)->nullable()->comment('菜单图标');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('code', 'uk_admin_permissions_code');
            $table->index('parent_id', 'idx_admin_permissions_parent_id');
        });

        // 角色-权限关联表
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->comment('角色权限关联表');
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->unsignedInteger('permission_id')->comment('权限ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->primary(['role_id', 'permission_id']);
            $table->index('permission_id', 'idx_admin_role_permissions_permission_id');
        });

        // 管理员-角色关联表
        Schema::create('admin_user_roles', function (Blueprint $table) {
            $table->comment('管理员角色关联表');
            $table->unsignedInteger('admin_user_id')->comment('管理员ID');
            $table->unsignedInteger('role_id')->comment('角色ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->primary(['admin_user_id', 'role_id']);
            $table->index('role_id', 'idx_admin_user_roles_role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_roles');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_users');
    }
};
