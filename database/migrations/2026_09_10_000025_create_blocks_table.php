<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('block_name', 100)->comment('区块名称');
            $table->string('block_type', 30)->comment('区块类型：header/footer/banner/content/sidebar/custom');
            $table->longText('content')->comment('区块内容（HTML/JSON）');
            $table->text('css')->nullable()->comment('自定义CSS样式');
            $table->text('js')->nullable()->comment('自定义JS脚本');
            $table->unsignedTinyInteger('is_global')->default(0)->comment('是否全局区块（全站复用）：1是，0否');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index('block_type');
            $table->index('is_global');
            $table->comment('区块表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
