<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('操作用户ID');
            $table->string('user_name', 60)->default('')->comment('操作用户名（冗余，防用户被删后无记录）');
            $table->string('client_ip', 45)->default('')->comment('客户端IP（支持IPv6）');
            $table->string('user_agent')->default('')->comment('客户端UA信息');
            $table->string('request_id', 64)->default('')->comment('请求追踪ID（关联一次请求的所有日志）');
            $table->string('event_type', 50)->comment('事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT');
            $table->string('target_type', 30)->comment('目标类型：content/term/user/attachment/config/comment/form_submission');
            $table->string('target_id', 64)->comment('目标ID（可能是数字或UUID）');
            $table->string('target_name')->default('')->comment('目标名称（冗余，便于展示）');
            $table->json('old_value')->nullable()->comment('修改前的数据快照（JSON）');
            $table->json('new_value')->nullable()->comment('修改后的数据快照（JSON）');
            $table->unsignedTinyInteger('operation_result')->default(1)->comment('操作结果：0失败，1成功');
            $table->string('error_message', 500)->default('')->comment('失败时的错误信息');
            $table->timestamp('created_at', 3)->useCurrent()->comment('创建时间（毫秒精度，只增不改）');
            $table->index('user_id');
            $table->index(['target_type', 'target_id']);
            $table->index('created_at');
            $table->comment('审计日志表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
