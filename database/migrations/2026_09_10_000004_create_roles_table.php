<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('角色名称（如：主编、运营）');
            $table->string('alias', 50)->comment('角色标识（如：chief_editor）')->unique();
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统内置（不可删除）：1是，0否');
            $table->string('description')->default('')->comment('角色描述');
            $table->timestamps();
            $table->comment('角色表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
