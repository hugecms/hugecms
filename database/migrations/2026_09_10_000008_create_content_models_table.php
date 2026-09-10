<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_models', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('模型名称（显示用，如：招聘信息）');
            $table->string('alias', 50)->comment('模型别名（代码/URL用，如：recruitment）')->unique();
            $table->string('table_name', 50)->comment('对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）')->unique();
            $table->string('description')->default('')->comment('模型描述');
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统内置：1是（不可删除），0否');
            $table->unsignedTinyInteger('is_commentable')->default(1)->comment('是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->integer('sort')->default(0)->comment('排序权重');
            $table->timestamps();
            $table->comment('内容模型表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_models');
    }
};
