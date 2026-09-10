<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable()->comment('发送者ID（NULL为系统）');
            $table->unsignedBigInteger('receiver_id')->comment('接收者用户ID');
            $table->string('msg_type', 30)->comment('消息类型：system/comment/mention/audit/marketing');
            $table->string('title', 200)->comment('消息标题');
            $table->text('content')->comment('消息内容（纯文本或HTML）');
            $table->string('link_url')->default('')->comment('跳转链接');
            $table->json('extra_data')->nullable()->comment('扩展数据（如关联内容ID）');
            $table->unsignedTinyInteger('is_read')->default(0)->comment('是否已读：0未读，1已读');
            $table->dateTime('read_at')->nullable()->comment('阅读时间');
            $table->unsignedTinyInteger('priority')->default(0)->comment('优先级：0普通，1重要，2紧急');
            $table->dateTime('expire_at')->nullable()->comment('消息过期时间（NULL永不过期）');
            $table->timestamps();
            $table->index(['receiver_id', 'is_read']);
            $table->index('created_at');
            $table->comment('站内消息表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
