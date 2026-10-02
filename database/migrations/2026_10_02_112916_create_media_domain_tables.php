<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->comment('媒体文件表');
            $table->id()->comment('媒体ID');
            $table->string('disk', 32)->comment('存储盘标识（gcs）');
            $table->string('path', 500)->comment('存储相对路径');
            $table->string('name')->comment('原始文件名');
            $table->string('alt')->nullable()->comment('替代文本（图片alt，SEO与无障碍）');
            $table->string('extension', 16)->comment('扩展名');
            $table->string('mime_type', 128)->comment('真实MIME类型');
            $table->unsignedBigInteger('size')->default(0)->comment('文件大小（字节）');
            $table->tinyInteger('type')->comment('类型：1-图片，2-视频，3-音频，4-附件');
            $table->unsignedInteger('width')->nullable()->comment('图片宽度（像素）');
            $table->unsignedInteger('height')->nullable()->comment('图片高度（像素）');
            $table->unsignedInteger('duration')->nullable()->comment('音视频时长（秒）');
            $table->char('hash', 40)->index()->comment('文件SHA1（秒传去重）');
            $table->json('thumbnails')->nullable()->comment('缩略图规格映射（V1.1 启用）');
            $table->unsignedBigInteger('uploaded_by')->comment('上传人ID（关联 users.id）');
            $table->timestamp('created_at')->nullable()->comment('上传时间');
            $table->index(['type', 'created_at']);
        });

        Schema::create('media_usages', function (Blueprint $table) {
            $table->comment('媒体引用表');
            $table->id()->comment('引用ID');
            $table->unsignedBigInteger('file_id')->comment('媒体ID（关联 media_files.id）');
            $table->string('subject_type', 64)->comment('引用主体类型（如 Post、Page、User）');
            $table->unsignedBigInteger('subject_id')->comment('引用主体ID');
            $table->string('field', 64)->comment('引用字段（cover/content/avatar）');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->index(['subject_type', 'subject_id']);
            $table->index('file_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_usages');
        Schema::dropIfExists('media_files');
    }
};
