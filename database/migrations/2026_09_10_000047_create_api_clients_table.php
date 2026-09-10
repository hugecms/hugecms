<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name', 50)->comment('客户端名称（如：小程序应用）');
            $table->string('client_id', 32)->comment('客户端ID（公钥）')->unique();
            $table->string('client_secret', 64)->comment('客户端密钥（应用层AES加密后存储）');
            $table->string('api_key', 64)->nullable()->comment('API Key（用于简化认证）');
            $table->string('grant_type', 30)->default('client_credentials')->comment('授权类型：client_credentials/password/authorization_code');
            $table->json('ip_whitelist')->nullable()->comment('IP白名单（JSON数组）');
            $table->unsignedInteger('rate_limit')->default(1000)->comment('每分钟请求数限制');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态：0禁用，1启用');
            $table->timestamp('last_used_at')->nullable()->comment('最后使用时间');
            $table->timestamps();
            $table->comment('API客户端表');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
