<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_banners', function (Blueprint $table) {
            $table->comment('轮播图/广告位表');
            $table->id()->comment('轮播ID');
            $table->string('position', 32)->index()->comment('展示位置（home_slide 首页轮播/sidebar 侧栏广告）');
            $table->string('title', 100)->comment('标题');
            $table->unsignedBigInteger('image_media_id')->comment('图片媒体ID（关联 media_files.id）');
            $table->unsignedBigInteger('mobile_image_media_id')->nullable()->comment('移动端图片媒体ID（关联 media_files.id，空则复用PC图）');
            $table->string('link_url', 500)->default('')->comment('跳转链接');
            $table->tinyInteger('target_type')->default(1)->comment('打开方式：1-当前窗口，2-新窗口');
            $table->integer('sort')->default(0)->comment('排序（值小在前）');
            $table->date('start_at')->nullable()->comment('投放开始日期（空为立即）');
            $table->date('end_at')->nullable()->comment('投放结束日期（空为长期）');
            $table->tinyInteger('status')->default(1)->comment('状态：1-启用，2-禁用');
            $table->timestamps();
        });

        Schema::create('site_friend_links', function (Blueprint $table) {
            $table->comment('友情链接表');
            $table->id()->comment('友链ID');
            $table->string('name', 64)->comment('站点名称');
            $table->string('url', 500)->comment('站点链接');
            $table->unsignedBigInteger('logo_media_id')->nullable()->comment('LOGO媒体ID（关联 media_files.id）');
            $table->integer('sort')->default(0)->comment('排序（值小在前）');
            $table->tinyInteger('status')->default(1)->comment('状态：1-启用，2-禁用');
            $table->timestamps();
        });

        Schema::create('site_feedbacks', function (Blueprint $table) {
            $table->comment('留言信息表');
            $table->id()->comment('留言ID');
            $table->string('name', 64)->comment('联系人');
            $table->string('phone', 20)->comment('联系电话');
            $table->string('email')->nullable()->comment('联系邮箱');
            $table->string('subject', 200)->nullable()->comment('留言主题');
            $table->text('content')->comment('留言内容');
            $table->string('ip', 45)->nullable()->comment('提交IP');
            $table->tinyInteger('status')->default(1)->comment('状态：1-未处理，2-已处理');
            $table->timestamp('handled_at')->nullable()->comment('处理时间');
            $table->unsignedBigInteger('handled_by')->nullable()->comment('处理人ID（关联 users.id）');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_feedbacks');
        Schema::dropIfExists('site_friend_links');
        Schema::dropIfExists('site_banners');
    }
};
