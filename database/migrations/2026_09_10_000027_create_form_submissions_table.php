<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id')->comment('关联表单模板ID');
            $table->json('submission_data')->comment('用户提交的具体表单数据（JSON）');
            $table->string('submitter_ip', 45)->default('')->comment('提交者IP');
            $table->string('user_agent')->nullable()->comment('提交者UA');
            $table->timestamp('created_at')->useCurrent();
            $table->index('form_id');
            $table->index('created_at');
            $table->foreign('form_id')->references('id')->on('form_templates')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('表单提交表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
