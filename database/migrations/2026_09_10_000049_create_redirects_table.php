<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_path', 500)->comment('来源路径（站内相对路径，以 / 开头）')->unique();
            $table->string('target_path', 500)->comment('目标路径（相对路径或完整URL）');
            $table->unsignedSmallInteger('status_code')->default(301)->comment('HTTP状态码：301永久重定向，302临时重定向');
            $table->unsignedBigInteger('hits')->default(0)->comment('命中次数（冗余计数，事务内维护）');
            $table->dateTime('last_hit_at')->nullable()->comment('最后命中时间');
            $table->string('remark')->default('')->comment('备注（如：slug 改版、栏目迁移）');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0停用，1启用');
            $table->timestamps();
            $table->index('status');
            $table->comment('重定向表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
