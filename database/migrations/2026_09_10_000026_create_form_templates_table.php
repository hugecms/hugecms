<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('表单名称（如：在线报名表）');
            $table->string('alias', 50)->comment('表单标识（用于代码调用）')->unique();
            $table->json('fields_config')->comment('字段配置（JSON数组）：字段名、类型、校验规则、选项等');
            $table->unsignedInteger('submit_count')->default(0)->comment('提交次数统计');
            $table->unsignedTinyInteger('is_active')->default(1)->comment('是否启用：1是，0否');
            $table->string('success_message')->default('提交成功！')->comment('提交成功提示语');
            $table->timestamps();
            $table->comment('表单模板表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_templates');
    }
};
