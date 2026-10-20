<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 客服域：会话、消息、客服工作状态、话术库、机器人规则、常见问题
     * IM 客服 + 机器人分流模型：客服账号复用 admin_users（RBAC 控制权限），不建独立账号体系
     * 实时推送由 workerman/gateway-worker 承载，本域表只做持久化；离线留言经 notifications 站内信提醒
     */
    public function up(): void
    {
        // 客服会话表：用户一次咨询一个会话，进行中会话复用；source 记录咨询来源上下文
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->comment('客服会话表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('发起用户ID');
            $table->unsignedInteger('server_id')->default(0)->comment('接待客服管理员ID,0=未分配(机器人/排队)');
            $table->string('source_type', 20)->default('none')->comment('咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询');
            $table->unsignedInteger('source_id')->default(0)->comment('来源ID,商品ID或订单ID');
            $table->tinyInteger('status')->default(1)->comment('状态:1=进行中,2=已结束');
            $table->unsignedInteger('user_last_read_id')->default(0)->comment('用户已读消息水位(消息ID),会话级已读');
            $table->unsignedInteger('server_last_read_id')->default(0)->comment('客服已读消息水位(消息ID)');
            $table->tinyInteger('rating')->default(0)->comment('满意度评分:0=未评,1-5星');
            $table->dateTime('rated_at')->nullable()->comment('评分时间');
            $table->dateTime('assigned_at')->nullable()->comment('分配客服时间');
            $table->dateTime('closed_at')->nullable()->comment('结束时间');
            $table->string('close_reason', 50)->nullable()->comment('结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭');
            $table->timestamps();
            $table->index(['user_id', 'status'], 'idx_chat_sessions_user_status');
            $table->index(['server_id', 'status'], 'idx_chat_sessions_server_status');
            $table->index('updated_at', 'idx_chat_sessions_updated_at');
        });

        // 客服消息表：分配/转接等流转事件以 system 消息落库，全程可追溯；机器人消息 sender_type=bot
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->comment('客服消息表');
            $table->id();
            $table->unsignedInteger('session_id')->comment('会话ID');
            $table->string('sender_type', 20)->comment('发送者类型:user=用户,admin=客服,bot=机器人,system=系统');
            $table->unsignedInteger('sender_id')->default(0)->comment('发送者ID,system为0');
            $table->string('type', 20)->default('text')->comment('消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件');
            $table->string('content', 1000)->comment('消息内容,文本或卡片数据(JSON)');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间,消息只追加不修改');
            $table->index('session_id', 'idx_chat_messages_session_id');
        });

        // 客服工作状态表：分配策略为空闲优先(current_sessions < max_sessions 且在线)
        Schema::create('chat_services', function (Blueprint $table) {
            $table->comment('客服工作状态表');
            $table->id();
            $table->unsignedInteger('admin_user_id')->comment('客服管理员ID,关联admin_users');
            $table->tinyInteger('online_status')->default(0)->comment('在线状态:0=离线,1=在线,2=忙碌');
            $table->unsignedInteger('max_sessions')->default(10)->comment('最大接待会话数');
            $table->unsignedInteger('current_sessions')->default(0)->comment('当前接待数,分配依据,冗余计数');
            $table->timestamps();
            $table->unique('admin_user_id', 'uk_chat_services_admin_user_id');
        });

        // 客服话术库表：工作台快捷回复
        Schema::create('chat_quick_replies', function (Blueprint $table) {
            $table->comment('客服话术库表');
            $table->id();
            $table->string('group', 20)->default('common')->comment('分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流');
            $table->string('title', 50)->comment('话术标题');
            $table->string('content', 1000)->comment('话术内容');
            $table->unsignedInteger('use_count')->default(0)->comment('使用次数');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index('group', 'idx_chat_quick_replies_group');
        });

        // 机器人自动回复规则表：关键词命中触发，按 sort 升序优先匹配；转人工规则兜底
        Schema::create('chat_bot_rules', function (Blueprint $table) {
            $table->comment('机器人自动回复规则表');
            $table->id();
            $table->string('keyword', 50)->comment('命中关键词,消息包含即触发');
            $table->tinyInteger('reply_type')->default(1)->comment('回复类型:1=文本,2=FAQ,3=转人工');
            $table->string('content', 1000)->nullable()->comment('回复内容,文本回复时用');
            $table->unsignedInteger('faq_id')->nullable()->comment('FAQ ID,FAQ回复时用');
            $table->unsignedInteger('hit_count')->default(0)->comment('命中次数,运营优化依据');
            $table->integer('sort')->default(0)->comment('排序,小者优先匹配');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
        });

        // 常见问题表：自助服务与机器人 FAQ 回复共用
        Schema::create('chat_faqs', function (Blueprint $table) {
            $table->comment('常见问题表');
            $table->id();
            $table->string('category', 20)->default('pre_sales')->comment('分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户');
            $table->string('question', 200)->comment('问题');
            $table->string('answer', 1000)->comment('答案');
            $table->unsignedInteger('views')->default(0)->comment('浏览量');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=隐藏,1=显示');
            $table->timestamps();
            $table->index('category', 'idx_chat_faqs_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_faqs');
        Schema::dropIfExists('chat_bot_rules');
        Schema::dropIfExists('chat_quick_replies');
        Schema::dropIfExists('chat_services');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
