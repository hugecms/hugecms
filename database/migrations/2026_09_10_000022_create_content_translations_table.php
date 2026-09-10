<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('关联内容主表ID');
            $table->string('locale', 10)->comment('语言代码（如：zh_CN, en_US）');
            $table->string('title', 200)->comment('翻译后的标题');
            $table->string('slug', 200)->comment('翻译后的URL别名');
            $table->text('excerpt')->nullable()->comment('翻译后的摘要');
            $table->longText('content')->nullable()->comment('翻译后的正文内容');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认语言版本');
            $table->timestamps();
            $table->unique(['content_id', 'locale']);
            $table->index(['locale', 'slug']);
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('内容翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};
