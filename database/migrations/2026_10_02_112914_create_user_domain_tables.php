<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // users 表业务字段扩展（表本体由 0001 迁移创建，保留标准命名）
        // 标准 RBAC：角色经 user_role 关联表多对多挂载，不设 role_id 单值列
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email')->comment('手机号（登录标识之一）');
            $table->unique('phone');
            $table->unsignedBigInteger('avatar_media_id')->nullable()->after('password')->comment('头像媒体ID（关联 media_files.id）');
            $table->tinyInteger('status')->default(1)->after('avatar_media_id')->comment('状态：1-启用，2-禁用');
            $table->unsignedInteger('login_failed_count')->default(0)->after('status')->comment('连续登录失败次数');
            $table->timestamp('locked_until')->nullable()->after('login_failed_count')->comment('锁定截止时间');
            $table->timestamp('last_login_at')->nullable()->after('locked_until')->comment('最后登录时间');
            $table->string('totp_secret')->nullable()->after('last_login_at')->comment('双因素认证密钥（V1.1 启用）');
            $table->index('status');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->comment('角色表');
            $table->id()->comment('角色ID');
            $table->string('name', 32)->comment('角色名称（超级管理员/管理员/编辑/作者/审核员）');
            $table->string('code', 32)->unique()->comment('角色标识（super_admin/admin/editor/author/auditor）');
            $table->string('description')->comment('角色说明');
            $table->tinyInteger('is_system')->default(2)->comment('是否内置角色：1-是，2-否');
            $table->tinyInteger('status')->default(1)->comment('状态：1-启用，2-禁用');
            $table->integer('sort')->default(0)->comment('排序（值小在前）');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->comment('权限点表');
            $table->id()->comment('权限ID');
            $table->string('name', 64)->unique()->comment('权限标识（如 post.publish、media.upload）');
            $table->string('module', 32)->comment('所属模块（content/media/user/setting）');
            $table->string('remark')->comment('权限说明');
            $table->tinyInteger('status')->default(1)->comment('状态：1-启用，2-禁用');
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->comment('用户角色关联表');
            $table->unsignedBigInteger('user_id')->comment('用户ID（关联 users.id）');
            $table->unsignedBigInteger('role_id')->comment('角色ID（关联 roles.id）');
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->comment('角色权限关联表');
            $table->unsignedBigInteger('role_id')->comment('角色ID（关联 roles.id）');
            $table->unsignedBigInteger('permission_id')->comment('权限ID（关联 permissions.id）');
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_audit_logs', function (Blueprint $table) {
            $table->comment('操作审计日志表');
            $table->id()->comment('日志ID');
            $table->unsignedBigInteger('user_id')->comment('操作人ID（关联 users.id）');
            $table->string('action', 64)->comment('动作标识（如 login、post.publish、user.disable）');
            $table->string('subject_type', 64)->nullable()->comment('操作对象类型（如 Post、Page、MediaFile）');
            $table->unsignedBigInteger('subject_id')->nullable()->comment('操作对象ID');
            $table->string('ip', 45)->comment('操作IP');
            $table->string('user_agent')->comment('浏览器标识');
            $table->json('detail')->nullable()->comment('详情（变更前后快照等）');
            $table->timestamp('created_at')->nullable()->comment('操作时间');
            $table->index(['user_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_audit_logs');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('user_roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropUnique(['phone']);
            $table->dropColumn([
                'phone',
                'avatar_media_id',
                'status',
                'login_failed_count',
                'locked_until',
                'last_login_at',
                'totp_secret',
            ]);
        });
    }
};
