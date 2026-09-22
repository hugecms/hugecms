<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('广告位名称（如：首页Banner）');
            $table->string('code', 50)->comment('广告位代码（如：home_banner，模板调用用）')->unique();
            $table->unsignedInteger('width')->default(0)->comment('建议宽度（像素）');
            $table->unsignedInteger('height')->default(0)->comment('建议高度（像素）');
            $table->string('ad_type', 20)->default('image')->comment('支持的广告类型：image/text/video/html');
            $table->unsignedInteger('max_count')->default(1)->comment('该广告位最多展示广告数量');
            $table->string('description')->default('')->comment('广告位描述');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->comment('广告位表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_positions');
    }
};
