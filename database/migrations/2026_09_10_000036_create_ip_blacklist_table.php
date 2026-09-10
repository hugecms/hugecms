<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->comment('IP地址（支持CIDR格式如：192.168.1.0/24）')->unique();
            $table->string('reason')->default('')->comment('封禁原因');
            $table->dateTime('block_until')->nullable()->comment('封禁截至时间（NULL表示永久）');
            $table->string('block_type', 20)->default('all')->comment('封禁类型：all全部/admin后台/api接口');
            $table->unsignedInteger('hit_count')->default(0)->comment('触发次数');
            $table->dateTime('last_hit_at')->nullable()->comment('最后触发时间');
            $table->unsignedBigInteger('operator_id')->nullable()->comment('操作人ID');
            $table->timestamps();
            $table->index('block_until');
            $table->comment('IP黑名单表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_blacklist');
    }
};
