<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('菜单名称（如：主导航）');
            $table->string('alias', 50)->comment('菜单标识（如：main_nav）')->unique();
            $table->string('description')->default('')->comment('描述');
            $table->timestamps();
            $table->comment('菜单集表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_menus');
    }
};
