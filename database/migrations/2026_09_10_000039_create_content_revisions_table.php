<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->comment('内容主表ID');
            $table->unsignedBigInteger('author_id')->default(0)->comment('修改人');
            $table->json('revision_data')->comment('修改时的全量数据快照（JSON）');
            $table->string('remark')->default('')->comment('修改备注');
            $table->timestamp('created_at')->useCurrent()->comment('修订时间');
            $table->index('content_id');
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('内容版本表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};
