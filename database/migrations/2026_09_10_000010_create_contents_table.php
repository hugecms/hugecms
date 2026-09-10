<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id')->comment('所属模型ID');
            $table->string('title', 200)->comment('内容标题');
            $table->string('slug', 200)->comment('URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）')->unique();
            $table->unsignedBigInteger('author_id')->default(0)->comment('发布者用户ID');
            $table->string('status', 20)->default('draft')->comment('状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站');
            $table->string('visibility', 20)->default('public')->comment('可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）');
            $table->string('password')->nullable()->comment('密码保护口令（visibility=password 时使用，哈希存储）');
            $table->unsignedBigInteger('views')->default(0)->comment('浏览量计数');
            $table->unsignedInteger('comment_count')->default(0)->comment('评论数（审核通过的冗余计数，避免列表页逐条COUNT）');
            $table->integer('sort')->default(0)->comment('手动排序权重（数值越大越靠前）');
            $table->unsignedTinyInteger('is_top')->default(0)->comment('是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）');
            $table->dateTime('published_at')->nullable()->comment('计划/实际发布时间');
            $table->string('audit_status', 20)->default('pending')->comment('审核状态：pending待审核/approved通过/rejected驳回');
            $table->string('audit_remark')->default('')->comment('审核备注（驳回原因）');
            $table->unsignedBigInteger('auditor_id')->nullable()->comment('审核人ID');
            $table->dateTime('audited_at')->nullable()->comment('审核时间');
            $table->timestamps();
            $table->index(['model_id', 'status', 'published_at']);
            $table->index(['author_id', 'status']);
            $table->index('published_at');
            $table->foreign('model_id')->references('id')->on('content_models')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('内容主表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
