<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxonomy_id')->comment('所属分类法ID');
            $table->string('name', 100)->comment('分类项名称（如：科技、体育）');
            $table->string('slug', 100)->comment('分类项别名（URL友好）');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID（0代表顶级）');
            $table->text('description')->nullable()->comment('分类项描述');
            $table->integer('sort')->default(0)->comment('排序权重');
            $table->unsignedInteger('content_count')->default(0)->comment('该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）');
            $table->timestamps();
            $table->unique(['taxonomy_id', 'slug']);
            $table->index('parent_id');
            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('分类项表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
