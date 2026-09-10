<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensitive_words', function (Blueprint $table) {
            $table->id();
            $table->string('word', 50)->comment('敏感词')->unique();
            $table->string('category', 30)->default('custom')->comment('分类：politics/porn/violence/spam/fraud/custom');
            $table->unsignedTinyInteger('severity')->default(1)->comment('严重程度：1警告，2拦截，3封禁');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->comment('敏感词表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensitive_words');
    }
};
