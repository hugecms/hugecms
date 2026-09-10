<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id')->comment('所属模型ID');
            $table->string('field_name', 60)->comment('字段业务英文名（如：salary）');
            $table->string('column_name', 60)->comment('物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）');
            $table->string('field_label', 100)->comment('字段显示标签（如：薪资范围）');
            $table->string('field_type', 30)->comment('字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json');
            $table->string('column_type', 30)->default('varchar(255)')->comment('数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json');
            $table->text('default_value')->nullable()->comment('默认值');
            $table->unsignedTinyInteger('is_required')->default(0)->comment('是否必填：0否，1是');
            $table->unsignedTinyInteger('is_unique')->default(0)->comment('值是否唯一：0否，1是');
            $table->json('validation_rules')->nullable()->comment('校验规则（JSON），如：{"max":100,"regex":"^[A-Z]"}');
            $table->json('extra_config')->nullable()->comment('额外配置（如select选项：{"options":["男","女"]}）');
            $table->integer('sort_order')->default(0)->comment('表单显示排序');
            $table->timestamps();
            $table->unique(['model_id', 'field_name']);
            $table->unique(['model_id', 'column_name']);
            $table->foreign('model_id')->references('id')->on('content_models')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('模型字段表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_fields');
    }
};
