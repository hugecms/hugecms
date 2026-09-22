<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id')->comment('所属广告位ID');
            $table->string('title', 100)->comment('广告标题');
            $table->string('ad_type', 20)->default('image')->comment('广告类型：image/text/video/html');
            $table->string('cover_image')->default('')->comment('广告图片/视频封面URL');
            $table->text('content')->nullable()->comment('广告内容（纯文本或HTML代码）');
            $table->string('link_url')->default('')->comment('广告跳转链接');
            $table->unsignedTinyInteger('link_target')->default(0)->comment('打开方式：0本窗口，1新窗口');
            $table->integer('sort')->default(0)->comment('展示排序（数值越小越靠前）');
            $table->dateTime('start_time')->nullable()->comment('投放开始时间（NULL表示立即开始）');
            $table->dateTime('end_time')->nullable()->comment('投放结束时间（NULL表示永久，过期由时间判断）');
            $table->unsignedInteger('display_limit')->default(0)->comment('展示次数上限（0不限）');
            $table->unsignedInteger('click_limit')->default(0)->comment('点击次数上限（0不限）');
            $table->unsignedInteger('display_count')->default(0)->comment('实际展示次数');
            $table->unsignedInteger('click_count')->default(0)->comment('实际点击次数');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index(['position_id', 'status']);
            $table->index(['start_time', 'end_time']);
            $table->foreign('position_id')->references('id')->on('ad_positions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('广告表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
