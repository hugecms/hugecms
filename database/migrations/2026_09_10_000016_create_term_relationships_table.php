<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('内容主表ID');
            $table->unsignedBigInteger('term_id')->comment('分类项ID');
            $table->integer('sort')->default(0)->comment('该内容在此分类下的自定义排序');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['content_id', 'term_id']);
            $table->index('term_id');
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('内容分类关联表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_relationships');
    }
};
