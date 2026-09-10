<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics_daily', function (Blueprint $table) {
            $table->id();
            $table->date('stat_date')->comment('统计日期')->unique();
            $table->unsignedInteger('new_contents')->default(0)->comment('新增内容数');
            $table->unsignedInteger('published_contents')->default(0)->comment('发布内容数');
            $table->unsignedInteger('total_contents')->default(0)->comment('累计内容总数');
            $table->unsignedBigInteger('total_views')->default(0)->comment('全站浏览量');
            $table->unsignedInteger('new_comments')->default(0)->comment('新增评论数');
            $table->unsignedInteger('new_users')->default(0)->comment('新增注册用户数');
            $table->unsignedInteger('active_users')->default(0)->comment('活跃用户数（登录/操作）');
            $table->unsignedInteger('total_users')->default(0)->comment('累计注册用户数');
            $table->timestamps();
            $table->comment('每日统计表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics_daily');
    }
};
