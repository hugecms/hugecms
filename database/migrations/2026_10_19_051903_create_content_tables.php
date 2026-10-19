<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 内容域：栏目、文章、标签、评论、单页、评论点赞
     */
    public function up(): void
    {
        // 栏目表：树形
        Schema::create('content_categories', function (Blueprint $table) {
            $table->comment('栏目表');
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID,0=顶级');
            $table->string('name', 50)->comment('栏目名称');
            $table->string('slug', 50)->comment('栏目标识,用于URL');
            $table->string('description', 255)->nullable()->comment('栏目描述');
            $table->string('seo_title', 100)->nullable()->comment('SEO标题');
            $table->string('seo_keywords', 255)->nullable()->comment('SEO关键词');
            $table->string('seo_description', 255)->nullable()->comment('SEO描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=隐藏,1=显示');
            $table->timestamps();
            $table->unique('slug', 'uk_categories_slug');
            $table->index('parent_id', 'idx_categories_parent_id');
        });

        // 文章主表：正文分离到 article_content，列表查询不拖大字段
        Schema::create('content_articles', function (Blueprint $table) {
            $table->comment('文章表');
            $table->id();
            $table->unsignedInteger('category_id')->comment('栏目ID');
            $table->string('title', 200)->comment('标题');
            $table->string('slug', 200)->nullable()->comment('文章标识,用于URL');
            $table->string('summary', 500)->nullable()->comment('摘要');
            $table->string('cover', 255)->nullable()->comment('封面图');
            $table->unsignedInteger('author_id')->nullable()->comment('作者管理员ID');
            $table->string('author', 50)->nullable()->comment('作者名称快照');
            $table->tinyInteger('is_top')->default(0)->comment('置顶:0=否,1=是');
            $table->tinyInteger('is_recommend')->default(0)->comment('推荐:0=否,1=是');
            $table->tinyInteger('status')->default(0)->comment('状态:0=草稿,1=发布,2=下架');
            $table->unsignedInteger('views')->default(0)->comment('浏览量');
            $table->dateTime('published_at')->nullable()->comment('发布时间');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('slug', 'uk_articles_slug');
            $table->index(['category_id', 'status'], 'idx_articles_category_status');
            $table->index('published_at', 'idx_articles_published_at');
        });

        // 文章正文表：与文章一对一
        Schema::create('content_article_contents', function (Blueprint $table) {
            $table->comment('文章正文表');
            $table->unsignedInteger('article_id')->comment('文章ID');
            $table->longText('content')->comment('正文(富文本)');
            $table->timestamps();
            $table->primary('article_id');
        });

        // 标签表
        Schema::create('content_tags', function (Blueprint $table) {
            $table->comment('标签表');
            $table->id();
            $table->string('name', 50)->comment('标签名');
            $table->timestamps();
            $table->unique('name', 'uk_tags_name');
        });

        // 文章-标签关联表
        Schema::create('content_article_tags', function (Blueprint $table) {
            $table->comment('文章标签关联表');
            $table->unsignedInteger('article_id')->comment('文章ID');
            $table->unsignedInteger('tag_id')->comment('标签ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->primary(['article_id', 'tag_id']);
            $table->index('tag_id', 'idx_article_tags_tag_id');
        });

        // 评论表：多态目标，文章/商品共用，parent_id 支持楼中楼
        Schema::create('content_comments', function (Blueprint $table) {
            $table->comment('评论表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('评论用户ID');
            $table->string('target_type', 20)->comment('目标类型:article,product');
            $table->unsignedInteger('target_id')->comment('目标ID');
            $table->unsignedInteger('parent_id')->default(0)->comment('父评论ID,0=顶级');
            $table->string('content', 1000)->comment('评论内容');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审,1=通过,2=拒绝');
            $table->string('ip', 45)->nullable()->comment('评论IP');
            $table->timestamps();
            $table->index(['target_type', 'target_id'], 'idx_comments_target');
            $table->index('user_id', 'idx_comments_user_id');
        });

        // 单页表：关于我们等固定页面
        Schema::create('content_pages', function (Blueprint $table) {
            $table->comment('单页表');
            $table->id();
            $table->string('title', 100)->comment('页面标题');
            $table->string('slug', 50)->comment('页面标识,如:about');
            $table->longText('content')->nullable()->comment('页面内容');
            $table->string('seo_title', 100)->nullable()->comment('SEO标题');
            $table->string('seo_keywords', 255)->nullable()->comment('SEO关键词');
            $table->string('seo_description', 255)->nullable()->comment('SEO描述');
            $table->tinyInteger('status')->default(1)->comment('状态:0=隐藏,1=显示');
            $table->timestamps();
            $table->unique('slug', 'uk_pages_slug');
        });

        // 评论点赞表：联合主键防重复点赞，风格同 user_followers
        Schema::create('content_comment_likes', function (Blueprint $table) {
            $table->comment('评论点赞表');
            $table->unsignedInteger('comment_id')->comment('评论ID');
            $table->unsignedInteger('user_id')->comment('点赞用户ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->primary(['comment_id', 'user_id']);
            $table->index('user_id', 'idx_comment_likes_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_comment_likes');
        Schema::dropIfExists('content_pages');
        Schema::dropIfExists('content_comments');
        Schema::dropIfExists('content_article_tags');
        Schema::dropIfExists('content_tags');
        Schema::dropIfExists('content_article_contents');
        Schema::dropIfExists('content_articles');
        Schema::dropIfExists('content_categories');
    }
};
