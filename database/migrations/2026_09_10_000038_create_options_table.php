<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('option_key', 100)->comment('配置键名（storage_config/smtp_config/comment_config等）')->unique();
            $table->longText('option_value')->comment('配置值（支持JSON复杂结构）');
            $table->unsignedTinyInteger('autoload')->default(1)->comment('启动时自动加载：0否，1是（配合 Laravel Cache 预热）');
            $table->timestamps();
            $table->comment('全局配置表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
