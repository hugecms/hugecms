<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id')->comment('关联分类项ID');
            $table->string('locale', 10)->comment('语言代码（如：zh_CN, en_US）');
            $table->string('name', 100)->comment('翻译后的分类项名称');
            $table->string('slug', 100)->comment('翻译后的URL别名');
            $table->text('description')->nullable()->comment('翻译后的描述');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认语言版本（与 terms 主表字段同源）');
            $table->timestamps();
            $table->unique(['term_id', 'locale']);
            $table->index(['locale', 'slug']);
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('分类项翻译表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_translations');
    }
};
