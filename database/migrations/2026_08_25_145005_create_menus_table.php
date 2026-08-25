<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->comment('后台菜单表');
            $table->id()->comment('主键 ID');
            $table->foreignId('parent_id')->nullable()->index()->constrained('menus')->nullOnDelete()->comment('父菜单 ID，顶级为 NULL');
            $table->string('name', 50)->comment('菜单名称');
            $table->string('icon', 50)->default('')->comment('图标类名');
            $table->string('route', 100)->default('')->comment('命名路由，分组为空');
            $table->unsignedSmallInteger('sort')->default(0)->comment('组内排序（升序）');
            $table->boolean('visible')->default(true)->comment('是否显示');
            $table->timestamps();
            $table->unique(['parent_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
