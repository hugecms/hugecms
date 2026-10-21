<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 前台用户账户表：仅存放登录凭证与账户状态，资料信息见 user_profiles 表
        Schema::create('users', function (Blueprint $table) {
            $table->comment('用户表');
            $table->id();
            $table->string('username')->comment('用户名');
            $table->string('password')->comment('密码哈希');
            $table->string('email', 100)->nullable()->comment('邮箱');
            $table->string('phone', 20)->nullable()->comment('手机号');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=正常');
            $table->unsignedInteger('rank_id')->default(0)->comment('会员等级ID,0=默认等级');
            $table->unsignedInteger('points')->default(0)->comment('积分余额');
            $table->string('share_code', 16)->nullable()->comment('推广码,分销/分享归因载体');
            $table->dateTime('last_login_time')->nullable()->comment('最后登录时间');
            $table->string('last_login_ip', 45)->nullable()->comment('最后登录IP');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('username', 'uk_users_username');
            $table->unique('email', 'uk_users_email');
            $table->unique('phone', 'uk_users_phone');
            $table->unique('share_code', 'uk_users_share_code');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
