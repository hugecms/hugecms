<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id')->comment('关联评论ID');
            $table->string('meta_key', 100)->default('')->comment('元数据键名（如 akismet_score）');
            $table->longText('meta_value')->nullable()->comment('元数据值');
            $table->timestamps();
            $table->unique(['comment_id', 'meta_key']);
            $table->foreign('comment_id')->references('id')->on('comments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->comment('评论元数据表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_meta');
    }
};
