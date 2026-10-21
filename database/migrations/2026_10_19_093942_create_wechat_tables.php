<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 微信域：公众号粉丝、消息模板、订阅授权、推送记录、渠道二维码、群发记录
     * 基于 w7corp/easywechat；小程序登录/公众号网页授权落 user_socials
     * (platform 约定:wechat_official=公众号,wechat_miniapp=小程序,wechat_open=扫码),UnionID 跨端打通
     * 触达降级链路：微信(模板/订阅消息) → 站内信(notifications) → 短信，统一查询视图 v_notify_touch_logs
     * 订阅消息为一次性授权，可发次数由 wechat_subscriptions 扣减管理
     * 全表携带 appid：openid/模板/二维码均按 appid 隔离，支持多公众号/小程序矩阵，换绑应用不串数据
     */
    public function up(): void
    {
        // 公众号粉丝表：粉丝资产独立于注册用户，user_id 在粉丝注册后回填
        Schema::create('wechat_fans', function (Blueprint $table) {
            $table->comment('公众号粉丝表');
            $table->id();
            $table->string('appid', 32)->comment('公众号AppID');
            $table->string('openid', 64)->comment('公众号OpenID');
            $table->string('unionid', 64)->nullable()->comment('开放平台UnionID,跨端识别同一用户');
            $table->string('nickname', 50)->nullable()->comment('昵称快照');
            $table->string('avatar', 255)->nullable()->comment('头像快照');
            $table->tinyInteger('subscribed')->default(1)->comment('关注状态:0=已取关,1=已关注');
            $table->dateTime('subscribed_at')->nullable()->comment('关注时间');
            $table->dateTime('unsubscribed_at')->nullable()->comment('取关时间');
            $table->string('subscribe_scene', 50)->nullable()->comment('关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码');
            $table->json('tags')->nullable()->comment('标签ID列表(JSON),群发圈人依据');
            $table->unsignedInteger('user_id')->nullable()->comment('关联注册用户ID,未注册为空');
            $table->timestamps();
            $table->unique(['appid', 'openid'], 'uk_wechat_fans_appid_openid');
            $table->index('unionid', 'idx_wechat_fans_unionid');
            $table->index('user_id', 'idx_wechat_fans_user_id');
        });

        // 微信消息模板表：业务场景到微信模板的映射，运营后台可配
        Schema::create('wechat_templates', function (Blueprint $table) {
            $table->comment('微信消息模板表');
            $table->id();
            $table->string('appid', 32)->comment('所属应用AppID,公众号或小程序');
            $table->string('scene', 50)->comment('业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等');
            $table->tinyInteger('type')->default(1)->comment('模板类型:1=公众号模板消息,2=小程序订阅消息');
            $table->string('template_id', 64)->comment('微信模板ID');
            $table->string('name', 100)->comment('模板名称,运营识别用');
            $table->json('fields')->nullable()->comment('字段映射(JSON):业务字段到模板参数的映射');
            $table->string('page', 100)->nullable()->comment('点击跳转页面路径,如:pages/order/detail?id=1');
            $table->unsignedInteger('send_count')->default(0)->comment('发送次数');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->unique(['appid', 'scene', 'type'], 'uk_wechat_templates_app_scene_type');
        });

        // 用户订阅授权表：一次性订阅消息次数账本，授权加一发送减一
        Schema::create('wechat_subscriptions', function (Blueprint $table) {
            $table->comment('用户订阅授权表');
            $table->id();
            $table->string('appid', 32)->comment('授权所在小程序AppID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('template_id')->comment('模板ID,关联wechat_templates');
            $table->unsignedInteger('times')->default(0)->comment('累计授权次数');
            $table->unsignedInteger('remain')->default(0)->comment('剩余可发次数,发送成功后扣减');
            $table->dateTime('last_subscribed_at')->nullable()->comment('最近授权时间');
            $table->timestamps();
            $table->unique(['user_id', 'appid', 'template_id'], 'uk_wechat_subscriptions_user_app_template');
        });

        // 微信消息推送记录表：发送留痕与失败排查，失败按 error 判断是否走降级链路
        Schema::create('wechat_messages', function (Blueprint $table) {
            $table->comment('微信消息推送记录表');
            $table->id();
            $table->string('appid', 32)->comment('发送所用应用AppID');
            $table->unsignedInteger('user_id')->comment('目标用户ID');
            $table->unsignedInteger('template_id')->comment('模板ID');
            $table->string('scene', 50)->comment('业务场景,同模板表scene');
            $table->unsignedInteger('related_id')->nullable()->comment('关联业务ID,如订单ID');
            $table->string('openid', 64)->comment('接收者OpenID快照');
            $table->text('content')->nullable()->comment('推送内容快照(JSON)');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待发送,1=成功,2=失败');
            $table->string('error', 255)->nullable()->comment('失败原因,如:用户未关注/订阅次数用尽');
            $table->dateTime('sent_at')->nullable()->comment('发送时间');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['user_id', 'status'], 'idx_wechat_messages_user_status');
            $table->index('template_id', 'idx_wechat_messages_template_id');
        });

        // 微信渠道二维码表：带参二维码，分销邀请码扫码即建立绑定关系,扫码/注册转化统计
        Schema::create('wechat_qrcodes', function (Blueprint $table) {
            $table->comment('微信渠道二维码表');
            $table->id();
            $table->string('appid', 32)->comment('二维码所属应用AppID,公众号带参码或小程序码');
            $table->string('scene_str', 64)->comment('场景值,同一应用下唯一');
            $table->string('name', 100)->comment('渠道码名称,如:分销员张三专属码');
            $table->string('type', 20)->default('distributor')->comment('类型:distributor=分销邀请,product=商品推广,channel=渠道投放');
            $table->unsignedInteger('target_id')->default(0)->comment('绑定目标ID,分销码=分销员用户ID,商品码=商品ID');
            $table->unsignedInteger('scan_count')->default(0)->comment('扫码次数');
            $table->unsignedInteger('register_count')->default(0)->comment('扫码注册转化数');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->unique(['appid', 'scene_str'], 'uk_wechat_qrcodes_appid_scene_str');
            $table->index(['type', 'target_id'], 'idx_wechat_qrcodes_type_target');
        });

        // 公众号群发记录表：运营群发留痕，msg_id 用于回拉微信侧送达结果
        Schema::create('wechat_broadcasts', function (Blueprint $table) {
            $table->comment('公众号群发记录表');
            $table->id();
            $table->string('appid', 32)->comment('群发公众号AppID');
            $table->string('type', 20)->default('news')->comment('类型:news=图文,text=文本,image=图片');
            $table->json('content')->nullable()->comment('群发内容(JSON),图文为素材列表');
            $table->string('target', 100)->nullable()->comment('目标:all=全部粉丝;或标签ID列表');
            $table->unsignedInteger('send_count')->default(0)->comment('送达数');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待发送,1=已发送,2=发送失败');
            $table->string('msg_id', 64)->nullable()->comment('微信群发消息ID,回拉发送结果用');
            $table->string('user_type', 20)->default('admin')->comment('操作账户类型:admin=后台,system=系统');
            $table->unsignedInteger('user_id')->nullable()->comment('操作人ID');
            $table->dateTime('sent_at')->nullable()->comment('发送时间');
            $table->timestamps();
        });

        // 统一触达记录视图：微信/站内信/短信三渠道 UNION，后台触达记录页统一查询入口
        // status 语义统一:0=待发送/发送中,1=成功,2=失败;站内信写入即送达,恒为1
        DB::statement('DROP VIEW IF EXISTS v_notify_touch_logs');
        DB::statement(<<<'SQL'
            CREATE VIEW v_notify_touch_logs AS
            SELECT id, 'wechat' AS channel, user_id, '' AS target_type, '' AS phone, scene, status, error, sent_at AS occurred_at
            FROM wechat_messages
            UNION ALL
            SELECT id, 'inbox', notifiable_id, notifiable_type, '', type, 1, NULL, created_at
            FROM notifications
            UNION ALL
            SELECT id, 'sms', NULL, '', phone, template, status, error, create_time
            FROM system_sms_logs
            SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_notify_touch_logs');
        Schema::dropIfExists('wechat_broadcasts');
        Schema::dropIfExists('wechat_qrcodes');
        Schema::dropIfExists('wechat_messages');
        Schema::dropIfExists('wechat_subscriptions');
        Schema::dropIfExists('wechat_templates');
        Schema::dropIfExists('wechat_fans');
    }
};
