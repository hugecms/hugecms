<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id')->comment('操作人ID');
            $table->string('task_name', 100)->comment('任务名称');
            $table->string('export_type', 30)->comment('导出类型：content/user/term/comment/statistics');
            $table->json('filter_conditions')->nullable()->comment('筛选条件（JSON）');
            $table->string('export_format', 20)->default('csv')->comment('导出格式：csv/excel/json/xml');
            $table->string('file_path')->default('')->comment('导出文件存储路径');
            $table->unsignedBigInteger('file_size')->default(0)->comment('文件大小（字节）');
            $table->unsignedInteger('total_records')->default(0)->comment('导出记录数');
            $table->string('status', 20)->default('pending')->comment('状态：pending/processing/completed/failed（执行走 Laravel 队列）');
            $table->timestamps();
            $table->dateTime('completed_at')->nullable()->comment('完成时间');
            $table->index('status');
            $table->comment('导出任务表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_tasks');
    }
};
