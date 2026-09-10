<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id')->comment('操作人ID');
            $table->string('task_name', 100)->comment('任务名称');
            $table->string('import_type', 30)->comment('导入类型：content/user/term/attachment/comment');
            $table->string('source_type', 20)->comment('来源类型：csv/excel/wordpress/json/api');
            $table->string('source_file')->default('')->comment('源文件路径');
            $table->json('config')->nullable()->comment('导入配置（字段映射、校验规则）');
            $table->unsignedInteger('total_records')->default(0)->comment('总记录数');
            $table->unsignedInteger('success_records')->default(0)->comment('成功导入数');
            $table->unsignedInteger('failed_records')->default(0)->comment('失败数');
            $table->json('error_log')->nullable()->comment('错误详情（JSON数组）');
            $table->string('status', 20)->default('pending')->comment('状态：pending/processing/completed/failed（执行走 Laravel 队列）');
            $table->timestamps();
            $table->dateTime('completed_at')->nullable()->comment('完成时间');
            $table->index('status');
            $table->comment('导入任务表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_tasks');
    }
};
