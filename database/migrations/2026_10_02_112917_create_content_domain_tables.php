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
        Schema::create('content_posts', function (Blueprint $table) {
            $table->comment('文章表');
            $table->id()->comment('文章ID');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->unique()->comment('URL标识（支持中文转拼音，可配置）');
            $table->string('excerpt', 500)->nullable()->comment('摘要');
            $table->unsignedBigInteger('cover_media_id')->nullable()->comment('封面媒体ID（关联 media_files.id）');
            $table->unsignedBigInteger('category_id')->comment('分类ID（关联 taxonomy_categories.id）');
            $table->longText('content')->comment('正文内容');
            $table->tinyInteger('content_format')->default(1)->comment('内容格式：1-Markdown，2-富文本HTML');
            $table->tinyInteger('status')->default(0)->comment('状态：0-草稿，10-待审核，20-已发布，30-定时发布，40-已下线');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->timestamp('scheduled_at')->nullable()->comment('定时发布时间');
            $table->tinyInteger('is_pinned')->default(2)->comment('是否置顶：1-是，2-否');
            $table->tinyInteger('is_recommended')->default(2)->comment('是否推荐：1-是，2-否');
            $table->tinyInteger('source_type')->default(1)->comment('来源类型：1-原创，2-转载');
            $table->string('source_url', 500)->nullable()->comment('转载原文链接');
            $table->string('author_name', 64)->nullable()->comment('自定义署名（空则显示创建者昵称）');
            $table->string('seo_title', 200)->nullable()->comment('SEO标题（空则按规则生成）');
            $table->string('seo_description', 300)->nullable()->comment('SEO描述');
            $table->string('seo_keywords', 200)->nullable()->comment('SEO关键词');
            $table->unsignedBigInteger('view_count')->default(0)->comment('阅读量（冗余计数）');
            $table->unsignedBigInteger('comment_count')->default(0)->comment('评论数（V1.1 启用维护）');
            $table->tinyInteger('allow_comment')->default(1)->comment('是否允许评论：1-允许，2-关闭');
            $table->unsignedBigInteger('created_by')->comment('创建人ID（关联 users.id）');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('最后编辑人ID（关联 users.id）');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间（回收站，保留30天）');
            $table->index(['category_id', 'status', 'published_at']);
            $table->index(['status', 'published_at']);
            $table->index(['is_pinned', 'published_at']);
        });

        Schema::create('content_pages', function (Blueprint $table) {
            $table->comment('单页表');
            $table->id()->comment('单页ID');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->unique()->comment('URL标识');
            $table->string('template', 64)->nullable()->comment('模板标识（空用默认 page 模板）');
            $table->longText('content')->comment('正文内容');
            $table->tinyInteger('content_format')->default(1)->comment('内容格式：1-Markdown，2-富文本HTML');
            $table->tinyInteger('status')->default(2)->comment('状态：1-已发布，2-草稿');
            $table->string('seo_title', 200)->nullable()->comment('SEO标题');
            $table->string('seo_description', 300)->nullable()->comment('SEO描述');
            $table->string('seo_keywords', 200)->nullable()->comment('SEO关键词');
            $table->unsignedBigInteger('created_by')->comment('创建人ID（关联 users.id）');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('最后编辑人ID（关联 users.id）');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间（回收站，保留30天）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_pages');
        Schema::dropIfExists('content_posts');
    }
};
