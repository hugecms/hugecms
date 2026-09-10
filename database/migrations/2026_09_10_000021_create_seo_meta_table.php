<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('target_type', 20)->comment('目标类型：content/term/custom_page');
            $table->unsignedBigInteger('target_id')->comment('对应的目标实体ID');
            $table->string('title', 200)->default('')->comment('SEO标题（浏览器Tab显示）');
            $table->string('keywords')->default('')->comment('SEO关键词（逗号分隔）');
            $table->string('description', 500)->default('')->comment('SEO描述（搜索结果展示）');
            $table->string('canonical_url')->default('')->comment('权威链接（防止重复页）');
            $table->string('robots', 100)->default('index,follow')->comment('机器人抓取策略');
            $table->timestamps();
            $table->unique(['target_type', 'target_id']);
            $table->index('target_id');
            $table->comment('SEO元数据表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
