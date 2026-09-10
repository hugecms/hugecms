<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级权限ID（0表示顶级）');
            $table->string('name', 50)->comment('权限名称（如：文章编辑）');
            $table->string('code', 100)->comment('权限代码（如：content:article:edit）')->unique();
            $table->string('module', 30)->default('content')->comment('所属模块（分组展示用）');
            $table->string('description')->default('')->comment('权限描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->index('parent_id');
            $table->comment('权限表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
