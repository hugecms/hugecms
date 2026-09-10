<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 默认「文章」模型的动态数据表（骨架 + 预置字段列）。
     * 运行期新建模型时，由程序按 model_fields 定义动态创建 data_{alias}
     * （如 data_recruitment）；列命名规范 field_{id}，与 model_fields.column_name 一一对应。
     * 表名在模型创建时由 alias 生成并写入 content_models.table_name，此后不可变。
     */
    public function up(): void
    {
        Schema::create('data_article', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('关联内容主表ID（一对一）');
            $table->string('field_1', 500)->default('')->comment('文章摘要（text）');
            $table->longText('field_2')->nullable()->comment('正文内容（rich_text）');
            $table->string('field_3')->default('')->comment('封面图，存附件ID或URL（image）');
            $table->json('_extra')->nullable()->comment('预留JSON扩展字段（未建模数据兜底）');
            $table->timestamps();
            $table->unique('content_id');
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('文章模型数据表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_article');
    }
};
