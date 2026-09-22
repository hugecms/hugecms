<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('分类法名称（如：文章分类、产品系列）');
            $table->string('alias', 50)->comment('分类法别名（如：article_cat）')->unique();
            $table->unsignedBigInteger('model_id')->nullable()->comment('绑定的模型ID（NULL表示全局分类）');
            $table->unsignedTinyInteger('is_hierarchical')->default(1)->comment('是否支持层级：1是（分类目录），0否（标签）');
            $table->string('description')->default('')->comment('描述');
            $table->timestamps();
            $table->index('model_id');
            $table->foreign('model_id')->references('id')->on('content_models')->nullOnDelete()->cascadeOnUpdate();
            $table->comment('分类法表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomies');
    }
};
