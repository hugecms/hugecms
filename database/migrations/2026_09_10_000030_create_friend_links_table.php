<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friend_links', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->default('default')->comment('链接分类（如：合作伙伴、友情链接）');
            $table->string('site_name', 100)->comment('网站名称');
            $table->string('site_url')->comment('网站URL');
            $table->string('logo_url')->default('')->comment('网站Logo URL');
            $table->string('description')->default('')->comment('网站描述');
            $table->string('contact_email', 100)->default('')->comment('联系人邮箱');
            $table->integer('sort')->default(0)->comment('排序权重');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0待审核，1已审核，2已拒绝');
            $table->timestamps();
            $table->index('status');
            $table->index('category');
            $table->comment('友情链接表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friend_links');
    }
};
