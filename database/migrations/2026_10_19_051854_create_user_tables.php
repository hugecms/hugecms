<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 用户域：资料、关注、收货地址、收藏、短信验证码、社交登录绑定、会员等级、钱包、浏览足迹、每日签到
     * 前台用户账户表 users 由基础迁移 0001_01_01_000000 创建
     * 站内通知复用 Laravel notifications 表，见 notify 域迁移
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

        // 会员等级表：按积分自动升级，等级价与等级折扣依据
        Schema::create('user_ranks', function (Blueprint $table) {
            $table->comment('会员等级表');
            $table->id();
            $table->string('name', 50)->comment('等级名称,如:普通会员');
            $table->unsignedInteger('min_points')->default(0)->comment('升级所需最低积分');
            $table->unsignedInteger('max_points')->default(0)->comment('积分上限,0=不限(最高级)');
            $table->unsignedTinyInteger('discount')->default(100)->comment('会员折扣,100=原价,95=95折');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
        });

        // 用户资金申请表：充值/提现申请单，与余额(users)、流水(user_account_logs)职责区分
        Schema::create('user_fund_applications', function (Blueprint $table) {
            $table->comment('用户资金申请表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('type', 20)->comment('类型:recharge=充值,withdraw=提现');
            $table->decimal('amount', 10, 2)->comment('申请金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审核,1=已到账,2=已拒绝');
            $table->string('user_note', 255)->nullable()->comment('用户备注');
            $table->string('admin_note', 255)->nullable()->comment('管理员备注');
            $table->dateTime('paid_at')->nullable()->comment('到账时间');
            $table->timestamps();
            $table->index(['user_id', 'status'], 'idx_user_fund_applications_user_status');
        });

        // 用户资金/积分流水表：只记变动量，余额以 users 表为准，对账依据
        Schema::create('user_account_logs', function (Blueprint $table) {
            $table->comment('用户资金/积分流水表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('change_type', 30)->comment('变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整');
            $table->decimal('change_amount', 10, 2)->default(0)->comment('资金变动,正数入负数出');
            $table->integer('change_points')->default(0)->comment('积分变动,正数入负数出');
            $table->string('description', 255)->comment('变动说明');
            $table->unsignedInteger('related_id')->nullable()->comment('关联单据ID');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_id', 'create_time'], 'idx_user_account_logs_user_time');
        });

        // 浏览足迹表：最近浏览的文章/商品，重复浏览仅更新时间（应用层 upsert touch），表不膨胀
        Schema::create('user_browsing_histories', function (Blueprint $table) {
            $table->comment('用户浏览足迹表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('target_type', 20)->comment('目标类型:article,product');
            $table->unsignedInteger('target_id')->comment('目标ID');
            $table->timestamp('create_time')->useCurrent()->comment('最近浏览时间,重复浏览更新');
            $table->unique(['user_id', 'target_type', 'target_id'], 'uk_user_browsing_histories_user_target');
            $table->index('create_time', 'idx_user_browsing_histories_create_time');
        });

        // 用户签到表：每人每天一条，连续签到天数由应用层比对昨日记录计算；奖励规则配置于 system_settings
        Schema::create('user_sign_ins', function (Blueprint $table) {
            $table->comment('用户签到表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->date('sign_date')->comment('签到日期');
            $table->unsignedInteger('continuous_days')->default(1)->comment('连续签到天数');
            $table->integer('points')->default(0)->comment('本次获得积分');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->unique(['user_id', 'sign_date'], 'uk_user_sign_ins_user_date');
            $table->index('sign_date', 'idx_user_sign_ins_sign_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sign_ins');
        Schema::dropIfExists('user_browsing_histories');
        Schema::dropIfExists('user_account_logs');
        Schema::dropIfExists('user_fund_applications');
        Schema::dropIfExists('user_ranks');
        Schema::dropIfExists('user_socials');
        Schema::dropIfExists('user_sms_codes');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_followers');
        Schema::dropIfExists('user_profiles');
    }
};
