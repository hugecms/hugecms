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
        Schema::create('taxonomy_categories', function (Blueprint $table) {
            $table->comment('分类表');
            $table->id()->comment('分类ID');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父分类ID（顶级为空，最多三级）');
            $table->string('name', 64)->comment('分类名称');
            $table->string('slug', 64)->unique()->comment('URL标识');
            $table->string('description')->nullable()->comment('分类描述');
            $table->string('seo_title')->nullable()->comment('SEO标题');
            $table->string('seo_description', 300)->nullable()->comment('SEO描述');
            $table->string('seo_keywords', 200)->nullable()->comment('SEO关键词');
            $table->integer('sort')->default(0)->comment('排序（值小在前）');
            $table->tinyInteger('status')->default(1)->comment('状态：1-启用，2-禁用');
            $table->integer('post_count')->default(0)->comment('文章数（冗余计数）');
            $table->timestamps();
            $table->index('parent_id');
        });

        Schema::create('taxonomy_tags', function (Blueprint $table) {
            $table->comment('标签表');
            $table->id()->comment('标签ID');
            $table->string('name', 64)->unique()->comment('标签名称');
            $table->string('slug', 64)->unique()->comment('URL标识');
            $table->integer('post_count')->default(0)->comment('引用文章数（冗余计数）');
            $table->timestamps();
        });

        Schema::create('taxonomy_post_tag', function (Blueprint $table) {
            $table->comment('文章标签关联表');
            $table->unsignedBigInteger('post_id')->comment('文章ID（关联 content_posts.id）');
            $table->unsignedBigInteger('tag_id')->comment('标签ID（关联 taxonomy_tags.id）');
            $table->unique(['post_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_post_tag');
        Schema::dropIfExists('taxonomy_tags');
        Schema::dropIfExists('taxonomy_categories');
    }
};
