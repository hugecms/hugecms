<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name', 50)->comment('告警规则名称');
            $table->string('metric', 50)->comment('监控指标：queue_length/disk_usage/cpu_usage/memory_usage/error_rate');
            $table->string('operator', 10)->comment('比较操作符：>/</>=/<=/=');
            $table->string('threshold', 50)->comment('阈值');
            $table->unsignedInteger('duration_seconds')->default(60)->comment('持续时长（秒）');
            $table->string('severity', 20)->default('warning')->comment('严重程度：warning/critical/emergency');
            $table->json('notify_channels')->nullable()->comment('通知方式：["email","sms","webhook"]');
            $table->unsignedTinyInteger('is_enabled')->default(1)->comment('是否启用：1是，0否');
            $table->timestamps();
            $table->index('metric');
            $table->comment('告警规则表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
