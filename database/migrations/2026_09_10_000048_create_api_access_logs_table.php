<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('client_id', 32)->comment('客户端ID');
            $table->string('endpoint', 100)->comment('API端点（如：/api/v1/contents）');
            $table->string('method', 10)->comment('HTTP方法：GET/POST/PUT/DELETE');
            $table->string('request_ip', 45)->default('')->comment('请求IP');
            $table->unsignedInteger('response_status')->default(200)->comment('HTTP响应状态码');
            $table->unsignedInteger('response_time_ms')->default(0)->comment('接口响应耗时（毫秒）');
            $table->timestamp('created_at')->useCurrent()->comment('请求时间（只增不改）');
            $table->index(['client_id', 'created_at']);
            $table->index('created_at');
            $table->comment('API访问日志表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_access_logs');
    }
};
