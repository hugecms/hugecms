<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 网站域：轮播图/广告位 + 友情链接
     */
    public function up(): void
    {
        // 轮播图/广告表：按广告位标识分组展示
        Schema::create('site_banners', function (Blueprint $table) {
            $table->comment('轮播图/广告表');
            $table->id();
            $table->string('position', 50)->comment('广告位标识,如:home_top');
            $table->string('title', 100)->comment('标题');
            $table->string('image', 255)->comment('图片地址');
            $table->string('link', 255)->nullable()->comment('跳转链接');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->dateTime('start_time')->nullable()->comment('投放开始时间');
            $table->dateTime('end_time')->nullable()->comment('投放结束时间');
            $table->timestamps();
            $table->index('position', 'idx_banners_position');
        });

        // 友情链接表
        Schema::create('site_links', function (Blueprint $table) {
            $table->comment('友情链接表');
            $table->id();
            $table->string('name', 50)->comment('站点名称');
            $table->string('url', 255)->comment('链接地址');
            $table->string('logo', 255)->nullable()->comment('站点LOGO');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_links');
        Schema::dropIfExists('site_banners');
    }
};
