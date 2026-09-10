<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name', 100)->comment('模板名称');
            $table->string('template_code', 50)->comment('模板代码（唯一标识）')->unique();
            $table->string('category', 30)->default('page')->comment('类别：page页面/post文章/term分类模板');
            $table->string('preview_image')->default('')->comment('预览图URL');
            $table->longText('content')->nullable()->comment('模板内容（HTML/JSON结构）');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认模板');
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统内置');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index('category');
            $table->comment('页面模板表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_templates');
    }
};
