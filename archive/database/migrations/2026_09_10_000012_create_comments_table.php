<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('关联内容主表ID（全模型通用）');
            $table->unsignedBigInteger('user_id')->nullable()->comment('评论者用户ID（NULL表示游客）');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父评论ID（0=顶级评论，支持楼中楼）');
            $table->unsignedBigInteger('reply_to_user_id')->nullable()->comment('被回复用户ID（渲染"回复@xxx"用）');
            $table->string('author_name', 50)->default('')->comment('评论者昵称（游客填写；登录用户冗余，防销号后无记录）');
            $table->string('author_email', 100)->default('')->comment('评论者邮箱（游客填写，用于头像/回复通知）');
            $table->string('author_url')->default('')->comment('评论者主页URL');
            $table->text('content')->comment('评论内容（纯文本；敏感词/反垃圾由插件钩子处理）');
            $table->string('ip', 45)->default('')->comment('评论者IP（反垃圾由插件处理）');
            $table->string('user_agent')->default('')->comment('评论者UA');
            $table->string('status', 20)->default('pending')->comment('状态：pending待审核/approved已通过/spam垃圾/trash回收站');
            $table->unsignedInteger('like_count')->default(0)->comment('点赞数');
            $table->timestamps();
            $table->index(['content_id', 'status', 'created_at']);
            $table->index('parent_id');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->comment('评论表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
