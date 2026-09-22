<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_push_queue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('被推送的内容ID');
            $table->string('push_type', 30)->comment('推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss');
            $table->json('push_data')->nullable()->comment('推送数据的最终形态（预处理后JSON）');
            $table->string('status', 20)->default('pending')->comment('状态：pending/processing/success/failed（执行与重试走 Laravel 队列）');
            $table->unsignedTinyInteger('retry_count')->default(0)->comment('已重试次数');
            $table->unsignedTinyInteger('max_retries')->default(3)->comment('最大重试次数');
            $table->string('error_message', 500)->default('')->comment('失败时的错误信息');
            $table->dateTime('finished_at')->nullable()->comment('完成时间');
            $table->timestamps();
            $table->index('status');
            $table->index('content_id');
            $table->comment('内容推送记录表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_push_queue');
    }
};
