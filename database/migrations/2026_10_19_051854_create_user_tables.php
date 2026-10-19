<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 用户域：资料、关注、收货地址、收藏、短信验证码、通知、社交登录绑定
     * 前台用户账户表 users 由基础迁移 0001_01_01_000000 创建
     */
    public function up(): void
    {
        // 用户资料表：与 users 表一对一（user_id 主键），存放展示类信息
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->comment('用户资料表');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('nickname', 50)->comment('昵称');
            $table->string('avatar', 255)->nullable()->comment('头像');
            $table->tinyInteger('gender')->default(0)->comment('性别:0=未知,1=男,2=女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('bio', 255)->nullable()->comment('个人简介');
            $table->string('location', 100)->nullable()->comment('所在地');
            $table->timestamps();
            $table->primary('user_id');
        });

        // 关注关系表：user_id 被 follower_id 关注，联合主键防重复关注
        Schema::create('user_followers', function (Blueprint $table) {
            $table->comment('关注关系表');
            $table->unsignedInteger('user_id')->comment('被关注用户ID');
            $table->unsignedInteger('follower_id')->comment('关注者用户ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->primary(['user_id', 'follower_id']);
            $table->index('follower_id', 'idx_user_followers_follower_id');
        });

        // 收货地址表
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->comment('用户收货地址表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('receiver_name', 50)->comment('收货人');
            $table->string('receiver_phone', 20)->comment('收货电话');
            $table->string('receiver_region', 100)->comment('省市区');
            $table->string('receiver_address', 255)->comment('详细地址');
            $table->tinyInteger('is_default')->default(0)->comment('默认地址:0=否,1=是');
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id', 'idx_user_addresses_user_id');
        });

        // 收藏表：多态目标，文章/商品共用，与 user_followers 同类互动关系
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->comment('用户收藏表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('target_type', 20)->comment('目标类型:article,product');
            $table->unsignedInteger('target_id')->comment('目标ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->unique(['user_id', 'target_type', 'target_id'], 'uk_user_favorites_user_target');
            $table->index(['target_type', 'target_id'], 'idx_user_favorites_target');
        });

        // 短信验证码表：注册/登录/找回密码，used_at+expire_at 控制一次性与时效
        Schema::create('user_sms_codes', function (Blueprint $table) {
            $table->comment('短信验证码表');
            $table->id();
            $table->string('phone', 20)->comment('手机号');
            $table->string('code', 10)->comment('验证码');
            $table->string('scene', 30)->comment('场景:register,login,reset_password');
            $table->dateTime('expire_at')->comment('过期时间');
            $table->dateTime('used_at')->nullable()->comment('使用时间');
            $table->string('ip', 45)->nullable()->comment('发送IP,用于防刷');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['phone', 'scene'], 'idx_user_sms_codes_phone_scene');
            $table->index('ip', 'idx_user_sms_codes_ip');
            $table->index('create_time', 'idx_user_sms_codes_create_time');
        });

        // 通知表：订单状态变更、评论回复等站内触达，read_at 为空即未读
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->comment('用户通知表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('接收用户ID');
            $table->string('type', 30)->comment('类型:order,comment,system');
            $table->string('title', 100)->comment('通知标题');
            $table->string('content', 500)->comment('通知内容');
            $table->string('params', 500)->nullable()->comment('跳转参数(JSON)');
            $table->dateTime('read_at')->nullable()->comment('已读时间,空=未读');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_id', 'read_at'], 'idx_user_notifications_user_read');
        });

        // 社交登录绑定表：一个用户可绑多个平台，未绑定用户首次授权时静默注册
        Schema::create('user_socials', function (Blueprint $table) {
            $table->comment('社交登录绑定表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('platform', 20)->comment('平台:wechat,qq,weibo,github');
            $table->string('open_id', 64)->comment('第三方OpenID');
            $table->string('union_id', 64)->nullable()->comment('第三方UnionID,如微信多应用互通');
            $table->string('nickname', 50)->nullable()->comment('第三方昵称快照');
            $table->string('avatar', 255)->nullable()->comment('第三方头像快照');
            $table->timestamps();
            $table->unique(['platform', 'open_id'], 'uk_user_socials_platform_openid');
            $table->index('user_id', 'idx_user_socials_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_socials');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('user_sms_codes');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_followers');
        Schema::dropIfExists('user_profiles');
    }
};
