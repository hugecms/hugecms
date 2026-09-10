<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->string('short_code', 20)->comment('短链代码（如：abc123）')->unique();
            $table->string('target_url', 500)->comment('原始目标URL');
            $table->string('title')->default('')->comment('链接标题/备注');
            $table->unsignedInteger('click_count')->default(0)->comment('点击次数');
            $table->string('qr_code_path')->default('')->comment('二维码图片存储路径');
            $table->dateTime('expire_at')->nullable()->comment('过期时间（NULL永不过期）');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index('expire_at');
            $table->comment('短链接表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
