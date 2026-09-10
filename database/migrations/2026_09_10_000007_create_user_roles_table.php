<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->string('data_scope', 20)->default('self')->comment('数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('用户角色关联表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
