<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->unsignedBigInteger('permission_id')->comment('权限ID');
            $table->unsignedTinyInteger('is_denied')->default(0)->comment('0=允许，1=拒绝（拒绝优先，覆盖性授权）');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('角色权限关联表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
