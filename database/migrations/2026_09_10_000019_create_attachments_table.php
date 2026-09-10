<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uploader_id')->default(0)->comment('上传者ID');
            $table->string('file_name')->comment('原始文件名');
            $table->string('file_path')->comment('物理存储相对路径');
            $table->string('storage_driver', 20)->default('local')->comment('存储驱动：local/oss/cos/s3');
            $table->string('storage_bucket', 100)->default('')->comment('存储桶名称（仅云存储有效）');
            $table->string('cdn_url')->default('')->comment('CDN加速访问URL');
            $table->unsignedBigInteger('file_size')->default(0)->comment('文件大小（字节）');
            $table->string('mime_type', 100)->comment('MIME类型（如：image/jpeg）');
            $table->unsignedInteger('width')->default(0)->comment('图片宽度（仅图片）');
            $table->unsignedInteger('height')->default(0)->comment('图片高度（仅图片）');
            $table->string('alt_text')->nullable()->comment('SEO替代文本');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->index('uploader_id');
            $table->index('mime_type');
            $table->comment('附件表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
