<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 消息域：站内通知复用 Laravel 集成 Notification（database channel）
     * 邮件走 Notification 邮件通道，发送队列用框架 jobs，不自建模板与队列表
     */
    public function up(): void
    {
        // 邮件订阅表：订阅/退订与退订令牌，营销邮件触达名单
        Schema::create('email_subscriptions', function (Blueprint $table) {
            $table->comment('邮件订阅表');
            $table->id();
            $table->string('email', 100)->comment('订阅邮箱');
            $table->tinyInteger('status')->default(1)->comment('状态:0=已退订,1=订阅中');
            $table->string('token', 64)->nullable()->comment('退订令牌');
            $table->timestamps();
            $table->unique('email', 'uk_email_subscriptions_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_subscriptions');
    }
};
