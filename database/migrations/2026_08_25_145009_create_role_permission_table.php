<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->comment('角色-权限关联表');
            $table->foreignId('role_id')->constrained()->cascadeOnDelete()->comment('角色 ID');
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete()->comment('权限 ID');
            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};
