<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 100)->comment('站点名称');
            $table->string('site_code', 50)->comment('站点代码（子域名或标识）')->unique();
            $table->string('domain', 200)->comment('主域名（如：www.example.com）')->unique();
            $table->json('domains')->nullable()->comment('附加域名列表（JSON数组）');
            $table->string('site_logo')->default('')->comment('站点Logo');
            $table->string('favicon')->default('')->comment('站点图标');
            $table->string('timezone', 50)->default('Asia/Shanghai')->comment('时区');
            $table->string('language', 10)->default('zh_CN')->comment('默认语言');
            $table->unsignedBigInteger('template_id')->nullable()->comment('当前使用的模板ID（关联 page_templates，逻辑关联）');
            $table->json('config')->nullable()->comment('站点配置（SEO默认值、社交分享等）');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->comment('站点表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
