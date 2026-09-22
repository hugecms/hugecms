<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->comment('所属菜单集');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID（0代表顶级）');
            $table->string('title', 100)->comment('菜单显示标题');
            $table->string('link_type', 20)->default('custom')->comment('链接类型：custom自定义/content内容/term分类');
            $table->string('link_value')->comment('链接目标值（自定义URL 或 content_id/term_id）');
            $table->unsignedTinyInteger('open_type')->default(0)->comment('打开方式：0本窗口，1新窗口');
            $table->string('icon', 100)->default('')->comment('小图标CSS类');
            $table->unsignedTinyInteger('is_active')->default(1)->comment('是否启用：1是，0否');
            $table->integer('sort')->default(0)->comment('排序权重');
            $table->timestamps();
            $table->index(['menu_id', 'parent_id']);
            $table->foreign('menu_id')->references('id')->on('nav_menus')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('菜单项表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
