<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_health', function (Blueprint $table) {
            $table->id();
            $table->string('check_type', 30)->comment('检查类型：database/redis/queue/storage/api/disk_space');
            $table->string('check_status', 20)->comment('状态：healthy/warning/critical/unknown');
            $table->string('check_value')->default('')->comment('检查值（如：磁盘使用率85%）');
            $table->string('threshold', 100)->default('')->comment('阈值配置');
            $table->string('error_message', 500)->default('')->comment('异常信息');
            $table->timestamp('checked_at')->useCurrent()->comment('检查时间');
            $table->index(['check_type', 'checked_at']);
            $table->comment('系统健康检查表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_health');
    }
};
