<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 用户域：资料、关注、收货地址、收藏、短信验证码、社交登录绑定、会员等级、资金账户（余额/冻结双字段+双快照流水）、
     * 积分流水、提现单状态机、浏览足迹、每日签到
     * 前台用户账户表 users 由基础迁移 0001_01_01_000000 创建（users 仅存积分余额，资金余额独立 user_accounts）
     * 提现走 user_withdraw_orders 状态机（冻结扣 user_accounts.frozen）；充值无需申请单——在线支付走 payments(biz_type=recharge) 回调入账，人工到账走 UserAccountService::adjust
     * 站内通知复用 Laravel notifications 表，见 notify 域迁移
     */
    public function up(): void
    {
        // 用户资料表：与 users 表一对一（user_id 主键），存放展示类信息
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->comment('用户资料表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('nickname', 50)->comment('昵称');
            $table->string('avatar', 255)->nullable()->comment('头像');
            $table->tinyInteger('gender')->default(0)->comment('性别:0=未知,1=男,2=女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('bio', 255)->nullable()->comment('个人简介');
            $table->string('location', 100)->nullable()->comment('所在地');
            $table->timestamps();
            $table->unique('user_id');
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
            $table->string('receiver_region', 100)->comment('省市区(合并展示用)');
            $table->string('receiver_province', 50)->nullable()->comment('省');
            $table->string('receiver_city', 50)->nullable()->comment('市');
            $table->string('receiver_district', 50)->nullable()->comment('区县');
            $table->string('receiver_province_code', 20)->nullable()->comment('省行政区划码,物流面单');
            $table->string('receiver_city_code', 20)->nullable()->comment('市行政区划码');
            $table->string('receiver_district_code', 20)->nullable()->comment('区县行政区划码');
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
            $table->string('target_type', 20)->comment('目标类型:article=文章,product=商品');
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
            $table->string('scene', 30)->comment('场景:register=注册,login=登录,reset_password=重置密码');
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
            $table->string('platform', 20)->comment('平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub');
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

        // 用户账户表：余额独立账户化，balance 可为负（欠款，不可用于消费/提现），frozen 为提现在途冻结
        // 可用余额 = balance - frozen；任何变动必须落 user_account_logs 双快照流水
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->comment('用户账户表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->decimal('balance', 10, 2)->default(0)->comment('账户余额,可为负=欠款,欠款不可用于消费/提现');
            $table->decimal('frozen', 10, 2)->default(0)->comment('冻结金额,提现审核/打款中在途');
            $table->timestamps();
            $table->unique('user_id', 'uk_user_accounts_user_id');
        });

        // 用户资金流水表：只追加，每笔账户变动落双快照（balance_after/frozen_after），对账与审计依据
        Schema::create('user_account_logs', function (Blueprint $table) {
            $table->comment('用户资金流水表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->tinyInteger('biz_type')->comment('业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整');
            $table->decimal('amount', 10, 2)->default(0)->comment('变动金额,正数入负数出');
            $table->decimal('balance_after', 10, 2)->comment('变动后余额快照');
            $table->decimal('frozen_after', 10, 2)->comment('变动后冻结快照');
            $table->string('biz_no', 64)->default('')->comment('关联业务单号,如提现单号/佣金记录号/充值单号');
            $table->string('description', 255)->default('')->comment('变动说明');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_id', 'create_time'], 'idx_user_account_logs_user_time');
            $table->index('biz_no', 'idx_user_account_logs_biz_no');
        });

        // 用户积分流水表：积分与资金流水拆分独立记录，users.points 为积分余额，只追加
        Schema::create('user_point_logs', function (Blueprint $table) {
            $table->comment('用户积分流水表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->tinyInteger('biz_type')->comment('业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)');
            $table->integer('change_points')->default(0)->comment('积分变动,正数入负数出');
            $table->unsignedInteger('points_after')->comment('变动后积分余额快照');
            $table->string('biz_no', 64)->default('')->comment('关联业务单号');
            $table->string('description', 255)->default('')->comment('变动说明');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_id', 'create_time'], 'idx_user_point_logs_user_time');
        });

        // 用户提现单表：状态机 10待审核→20审核通过→30打款中→40成功；10→50审核拒绝；30→60打款失败已回退
        // 微信商家转账本期不集成，打款走插件占位+人工标记；冻结走 user_accounts.frozen
        Schema::create('user_withdraw_orders', function (Blueprint $table) {
            $table->comment('用户提现单表');
            $table->id();
            $table->string('withdraw_no', 32)->comment('提现单号,应用层生成');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->decimal('amount', 10, 2)->comment('提现金额');
            $table->string('channel', 20)->default('manual')->comment('打款渠道:manual=人工线下打款;微信商家转账后续扩展');
            $table->string('channel_order_no', 64)->nullable()->comment('渠道打款单号,回填防重复打款');
            $table->string('account_type', 20)->default('')->comment('收款方式:bank=银行卡,wechat=微信,alipay=支付宝');
            $table->string('account_name', 50)->default('')->comment('收款人姓名');
            $table->string('account_no', 64)->default('')->comment('收款账号');
            $table->tinyInteger('status')->default(10)->comment('状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退');
            $table->string('user_note', 255)->default('')->comment('用户备注');
            $table->string('admin_note', 255)->default('')->comment('审核备注');
            $table->string('fail_reason', 255)->default('')->comment('打款失败原因');
            $table->dateTime('audit_at')->nullable()->comment('审核时间');
            $table->dateTime('paid_at')->nullable()->comment('打款标记时间(成功或失败)');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('withdraw_no', 'uk_user_withdraw_orders_withdraw_no');
            $table->unique(['channel', 'channel_order_no'], 'uk_user_withdraw_orders_channel_order_no');
            $table->index(['user_id', 'status'], 'idx_user_withdraw_orders_user_status');
        });

        // 浏览足迹表：最近浏览的文章/商品，重复浏览仅更新时间（应用层 upsert touch），表不膨胀
        Schema::create('user_browsing_histories', function (Blueprint $table) {
            $table->comment('用户浏览足迹表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('target_type', 20)->comment('目标类型:article=文章,product=商品');
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
        Schema::dropIfExists('user_withdraw_orders');
        Schema::dropIfExists('user_point_logs');
        Schema::dropIfExists('user_account_logs');
        Schema::dropIfExists('user_accounts');
        Schema::dropIfExists('user_ranks');
        Schema::dropIfExists('user_socials');
        Schema::dropIfExists('user_sms_codes');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_followers');
        Schema::dropIfExists('user_profiles');
    }
};
