<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->comment('权限点表');
            $table->id()->comment('主键 ID');
            $table->string('name', 100)->unique()->comment('权限标识（admin.模块.动作）');
            $table->string('title', 50)->comment('权限名称');
            $table->string('module', 50)->default('')->comment('归属模块');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
