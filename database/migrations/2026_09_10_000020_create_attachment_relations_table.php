<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachment_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachment_id')->comment('附件ID');
            $table->unsignedBigInteger('content_id')->comment('关联的内容ID');
            $table->string('field_key', 60)->default('content')->comment('关联到内容的哪个字段（如：封面图、详情图集）');
            $table->integer('sort')->default(0)->comment('在该内容下的排序');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['attachment_id', 'content_id', 'field_key']);
            $table->index('content_id');
            $table->foreign('attachment_id')->references('id')->on('attachments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('内容附件关联表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachment_relations');
    }
};
