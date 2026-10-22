<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 营销域：优惠券（类型/用户领取）、促销活动（满减/折扣，范围与阶梯拆子表）、秒杀/限时购、分销返佣（两级分销）
     * 促销范围/优惠参数为 promotion_id 关联子表（marketing_promotion_scopes/marketing_promotion_ladders），保存整体替换，算价引擎按 ladder 逐档匹配
     * 秒杀库存走 sold_stock 原子自增（where sold_stock + n <= total_stock），订单取消回补，变动记 marketing_flash_sale_logs
     * 佣金按 marketing_distribution_commission_rules（商品>分类>system_settings 全局兜底）计佣，保护期后结算入账 user_accounts（biz_type=1），
     * 保护期内退款置失效，已结算退款走负额冲销记录（reversal_of_id 回指，唯一索引允许多 NULL 即原始记录互不冲突）；提现走 user_withdraw_orders 状态机
     * 裂变三件套：拼团（团员 uk(team,user)+uk(order_no) 双重唯一防一单多团）、助力（参与 uk(activity,user) 一人一条，发奖完成态幂等，奖励对接券/积分流水）、
     * 砍价（current_price 条件更新防并发超砍，uk(order_no) NULL 不参与约束即未下单互不冲突）；助力/帮砍入口挂风控黑名单检查
     * 分享行为记录（marketing_share_records）只追加，是订单归因（orders.attributed_user_id）的判定来源
     */
    public function up(): void
    {
        // 优惠券类型表
        Schema::create('marketing_coupons', function (Blueprint $table) {
            $table->comment('优惠券类型表');
            $table->id();
            $table->string('name', 50)->comment('券名称');
            $table->string('type', 20)->comment('类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('使用门槛,0=无门槛');
            $table->decimal('reduce_amount', 10, 2)->nullable()->comment('满减金额,满减券必填');
            $table->decimal('discount_rate', 3, 1)->nullable()->comment('折扣率,如8.5表示85折,折扣券必填');
            $table->string('receive_type', 20)->default('manual')->comment('发放方式:manual=后台发放,order=下单赠送,register=注册赠送');
            $table->unsignedInteger('total')->default(0)->comment('发放总量,0=不限;领券事务条件更新 received_count < total 防超发');
            $table->unsignedInteger('received_count')->default(0)->comment('已领取数量,领券事务内条件更新 received_count < total 防超发');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人限领');
            $table->tinyInteger('valid_type')->default(1)->comment('有效期方式:1=固定区间(start_time/end_time),2=领取后N天');
            $table->unsignedInteger('valid_days')->nullable()->comment('领取后有效天数,valid_type=2 时必填');
            $table->dateTime('start_time')->nullable()->comment('有效期开始,空=领取即生效');
            $table->dateTime('end_time')->nullable()->comment('有效期结束');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
        });

        // 用户优惠券表：券的实例，领取与核销记录
        Schema::create('marketing_user_coupons', function (Blueprint $table) {
            $table->comment('用户优惠券表');
            $table->id();
            $table->unsignedInteger('coupon_id')->comment('优惠券类型ID');
            $table->unsignedInteger('user_id')->comment('持有用户ID,发放即创建归属');
            $table->unsignedInteger('order_id')->nullable()->comment('核销订单ID');
            $table->tinyInteger('status')->default(0)->comment('状态:0=未使用,1=已使用,2=已过期');
            $table->dateTime('used_at')->nullable()->comment('核销时间');
            $table->dateTime('expire_at')->nullable()->comment('过期时间,空=按券类型有效期');
            $table->timestamps();
            $table->index('coupon_id', 'idx_marketing_user_coupons_coupon_id');
            $table->index(['user_id', 'status'], 'idx_marketing_user_coupons_user_status');
        });

        // 促销活动表：满减/折扣主档，范围与优惠参数拆子表
        Schema::create('marketing_promotions', function (Blueprint $table) {
            $table->comment('促销活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->string('type', 20)->comment('类型:full_reduce=满减,discount=折扣');
            $table->string('range_type', 20)->default('all')->comment('适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品');
            $table->dateTime('start_time')->nullable()->comment('活动开始时间');
            $table->dateTime('end_time')->nullable()->comment('活动结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['status', 'start_time', 'end_time'], 'idx_marketing_promotions_status_time');
        });

        // 促销适用范围表：range_type=all 时无行，其余按 scope_type 逐条圈定
        Schema::create('marketing_promotion_scopes', function (Blueprint $table) {
            $table->comment('促销适用范围表');
            $table->id();
            $table->unsignedInteger('promotion_id')->comment('促销活动ID,关联marketing_promotions');
            $table->string('scope_type', 20)->comment('范围类型:category=分类,brand=品牌,product=商品;与父表range_type一致');
            $table->unsignedInteger('target_id')->comment('目标ID,按scope_type指向分类/品牌/商品');
            $table->timestamps();
            $table->unique(['promotion_id', 'scope_type', 'target_id'], 'uk_marketing_promotion_scopes_promotion_scope_target');
            $table->index(['scope_type', 'target_id'], 'idx_marketing_promotion_scopes_scope_target');
        });

        // 促销满减阶梯表：单档=无阶梯，多档=满减阶梯（如满100-10/满200-30）；优惠参数只存本表
        Schema::create('marketing_promotion_ladders', function (Blueprint $table) {
            $table->comment('促销满减阶梯表');
            $table->id();
            $table->unsignedInteger('promotion_id')->comment('促销活动ID,关联marketing_promotions');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('门槛金额,0=无门槛');
            $table->decimal('reduce_amount', 10, 2)->nullable()->comment('满减金额,full_reduce类型必填');
            $table->decimal('discount_rate', 3, 1)->nullable()->comment('折扣率,如8.5表示85折,discount类型必填');
            $table->timestamps();
            $table->unique(['promotion_id', 'min_amount'], 'uk_marketing_promotion_ladders_promotion_min_amount');
        });

        // 秒杀活动表：按时间段组织，同时间段内可含多个场次商品
        Schema::create('marketing_flash_sales', function (Blueprint $table) {
            $table->comment('秒杀活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['status', 'start_time', 'end_time'], 'idx_marketing_flash_sales_status_time');
        });

        // 秒杀商品表：活动维度的特价与限量，可购=total_stock-sold_stock；价格优先级:秒杀价>阶梯价>会员价>会员折扣
        Schema::create('marketing_flash_sale_items', function (Blueprint $table) {
            $table->comment('秒杀商品表');
            $table->id();
            $table->unsignedInteger('sale_id')->comment('秒杀活动ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->comment('SKU ID,关联product_skus');
            $table->decimal('original_price', 10, 2)->comment('原价快照');
            $table->decimal('price', 10, 2)->comment('秒杀价');
            $table->unsignedInteger('total_stock')->comment('秒杀总量');
            $table->unsignedInteger('sold_stock')->default(0)->comment('已售量,可购=total_stock-sold_stock');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人限购');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=下架,1=上架');
            $table->timestamps();
            $table->unique(['sale_id', 'product_id', 'sku_id'], 'uk_marketing_flash_sale_items_sale_product_sku');
            $table->index(['product_id', 'status'], 'idx_marketing_flash_sale_items_product_status');
        });

        // 佣金规则表：分类默认/商品覆盖两级比例，命中优先级 商品>分类>system_settings 全局兜底
        Schema::create('marketing_distribution_commission_rules', function (Blueprint $table) {
            $table->comment('佣金规则表');
            $table->id();
            $table->tinyInteger('scope_type')->comment('范围类型:1=分类默认,2=商品覆盖');
            $table->unsignedInteger('scope_id')->comment('目标ID,scope_type=1指向分类,2指向商品');
            $table->decimal('level1_rate', 5, 2)->comment('一级佣金比例(%),如5.00表示5%');
            $table->decimal('level2_rate', 5, 2)->comment('二级佣金比例(%)');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->unique(['scope_type', 'scope_id'], 'uk_marketing_distribution_commission_rules_scope_type_id');
            $table->index('status', 'idx_marketing_distribution_commission_rules_status');
        });

        // 分销员表：用户申请成为分销员，审核通过后可推广计佣
        Schema::create('marketing_distributors', function (Blueprint $table) {
            $table->comment('分销员表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('inviter_id')->default(0)->comment('上级分销员用户ID,0=无上级');
            $table->tinyInteger('level')->default(1)->comment('分销员等级:1=普通,2=高级');
            $table->decimal('total_commission', 10, 2)->default(0)->comment('累计佣金');
            $table->decimal('settled_commission', 10, 2)->default(0)->comment('已结算佣金,可提现=total-settled');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审核,1=通过,2=拒绝,3=冻结');
            $table->timestamps();
            $table->unique('user_id', 'uk_marketing_distributors_user_id');
            $table->index('inviter_id', 'idx_marketing_distributors_inviter_id');
        });

        // 分销绑定关系表：下单人与分销员的层级快照，绑定后下单即计佣，防层级变更漏算
        Schema::create('marketing_distribution_relations', function (Blueprint $table) {
            $table->comment('分销绑定关系表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('被绑定用户ID(下单人)');
            $table->unsignedInteger('distributor_id')->comment('分销员用户ID');
            $table->tinyInteger('level')->comment('层级:1=一级(直接邀请),2=二级(间接)');
            $table->timestamp('create_time')->useCurrent()->comment('绑定时间');
            $table->unique(['user_id', 'distributor_id'], 'uk_marketing_distribution_relations_user_distributor');
            $table->index('distributor_id', 'idx_marketing_distribution_relations_distributor_id');
        });

        // 分销佣金流水表：订单完成后按保护期生成待结算记录，到期结算入账 user_accounts
        // 保护期内退款置失效（未动资金）；已结算退款走负额冲销记录（reversal_of_id 回指原记录，唯一索引允许多 NULL 即原始记录互不冲突）
        Schema::create('marketing_distribution_commissions', function (Blueprint $table) {
            $table->comment('分销佣金流水表');
            $table->id();
            $table->unsignedInteger('distributor_id')->comment('分销员用户ID');
            $table->unsignedInteger('user_id')->comment('购买用户ID');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('order_item_id')->nullable()->comment('订单明细ID,空=整单计佣');
            $table->tinyInteger('level')->comment('层级:1=一级,2=二级');
            $table->decimal('order_amount', 10, 2)->comment('计佣基数(商品实付)');
            $table->decimal('rate', 5, 2)->comment('佣金比例(%),如5.00表示5%');
            $table->decimal('amount', 10, 2)->comment('佣金金额,冲销记录为负值');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款/原记录侧)');
            $table->dateTime('settled_at')->nullable()->comment('实际结算入账时间');
            $table->dateTime('settle_time')->nullable()->comment('应结算时间=订单完成时间+保护期,到期由结算流程入账');
            $table->unsignedInteger('reversal_record_id')->default(0)->comment('冲销记录ID,0=未被冲销,原记录侧回指负额冲销记录');
            $table->unsignedInteger('reversal_of_id')->nullable()->comment('原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录');
            $table->timestamps();
            $table->unique('reversal_of_id', 'uk_marketing_distribution_commissions_reversal_of');
            $table->index(['distributor_id', 'status'], 'idx_marketing_distribution_commissions_distributor_status');
            $table->index('order_id', 'idx_marketing_distribution_commissions_order_id');
        });

        // 秒杀库存流水表：sold_stock 每次原子变动的审计留痕，超卖/回补纠纷追溯依据
        Schema::create('marketing_flash_sale_logs', function (Blueprint $table) {
            $table->comment('秒杀库存流水表');
            $table->id();
            $table->unsignedInteger('flash_sale_item_id')->comment('秒杀商品ID,关联marketing_flash_sale_items');
            $table->unsignedInteger('order_id')->comment('关联订单ID,扣减与回补均关联订单');
            $table->integer('change_quantity')->comment('变动数量,正数扣减,负数回补');
            $table->unsignedInteger('before_sold_stock')->comment('变动前已售量');
            $table->unsignedInteger('after_sold_stock')->comment('变动后已售量');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('flash_sale_item_id', 'idx_marketing_flash_sale_logs_item');
            $table->index('order_id', 'idx_marketing_flash_sale_logs_order_id');
        });

        // 拼团活动表：成团人数/时限/限购，成团价对活动内 SKU 生效
        Schema::create('marketing_group_buy_activities', function (Blueprint $table) {
            $table->comment('拼团活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->unsignedInteger('spu_id')->comment('商品SPU ID');
            $table->unsignedInteger('group_size')->default(2)->comment('成团人数');
            $table->dateTime('start_time')->comment('活动开始时间');
            $table->dateTime('end_time')->comment('活动结束时间');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人限购(整个活动期)');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['spu_id', 'status'], 'idx_marketing_group_buy_activities_spu_status');
        });

        // 拼团场次商品表：SKU 级成团价，下单时快照进订单项 price；库存走常规 locked_stock 防超卖
        Schema::create('marketing_group_buy_items', function (Blueprint $table) {
            $table->comment('拼团场次商品表');
            $table->id();
            $table->unsignedInteger('activity_id')->comment('拼团活动ID');
            $table->unsignedInteger('sku_id')->comment('SKU ID');
            $table->decimal('group_price', 10, 2)->comment('该SKU成团价,下单快照进订单项price');
            $table->timestamps();
            $table->unique(['activity_id', 'sku_id'], 'uk_marketing_group_buy_items_activity_sku');
            $table->index('sku_id', 'idx_marketing_group_buy_items_sku_id');
        });

        // 拼团团表：人齐成团/超时解散，member_count 应用层与成员表同事务维护
        Schema::create('marketing_group_buy_teams', function (Blueprint $table) {
            $table->comment('拼团团表');
            $table->id();
            $table->unsignedInteger('activity_id')->comment('拼团活动ID');
            $table->unsignedInteger('leader_user_id')->comment('团长用户ID');
            $table->tinyInteger('status')->default(1)->comment('状态:1=拼团中,2=已成团,3=已解散(超时/取消)');
            $table->unsignedInteger('member_count')->default(1)->comment('当前成员数');
            $table->dateTime('expire_time')->comment('成团截止时间,超时扫描依据');
            $table->dateTime('success_time')->nullable()->comment('成团时间');
            $table->timestamps();
            $table->index(['activity_id', 'status'], 'idx_marketing_group_buy_teams_activity_status');
            $table->index(['status', 'expire_time'], 'idx_marketing_group_buy_teams_status_expire');
            $table->index('leader_user_id', 'idx_marketing_group_buy_teams_leader');
        });

        // 拼团团员表：uk(team,user) 一团一单 + uk(order_no) 一单一团，双重唯一防重复参团与刷单
        Schema::create('marketing_group_buy_team_members', function (Blueprint $table) {
            $table->comment('拼团团员表');
            $table->id();
            $table->unsignedInteger('team_id')->comment('团ID');
            $table->unsignedInteger('user_id')->comment('成员用户ID');
            $table->string('order_no', 30)->comment('成员订单号,关联orders.order_no');
            $table->dateTime('join_time')->comment('参团时间');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->unique(['team_id', 'user_id'], 'uk_marketing_group_buy_team_members_team_user');
            $table->unique('order_no', 'uk_marketing_group_buy_team_members_order_no');
            $table->index('user_id', 'idx_marketing_group_buy_team_members_user_id');
        });

        // 助力活动表：通用任务"邀N人得奖励"，奖励对接券或积分
        Schema::create('marketing_assist_activities', function (Blueprint $table) {
            $table->comment('助力活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->tinyInteger('reward_type')->comment('奖励类型:1=优惠券,2=积分');
            $table->unsignedInteger('reward_ref')->comment('奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config');
            $table->unsignedInteger('required_count')->comment('所需助力人数');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人可发起次数');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->json('config')->nullable()->comment('扩展参数(积分数/阶梯奖励等)');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
        });

        // 助力参与记录表：uk(activity,user) 一人一条，helper_count 达标触发发奖，完成态不重复发（发奖幂等）
        Schema::create('marketing_assist_records', function (Blueprint $table) {
            $table->comment('助力参与记录表');
            $table->id();
            $table->unsignedInteger('activity_id')->comment('助力活动ID');
            $table->unsignedInteger('user_id')->comment('发起人用户ID');
            $table->unsignedInteger('helper_count')->default(0)->comment('已助力人数,达required_count触发发奖');
            $table->tinyInteger('status')->default(1)->comment('状态:1=进行中,2=已完成发奖,3=已过期');
            $table->dateTime('finish_time')->nullable()->comment('完成时间');
            $table->timestamps();
            $table->unique(['activity_id', 'user_id'], 'uk_marketing_assist_records_activity_user');
            $table->index('user_id', 'idx_marketing_assist_records_user_id');
        });

        // 助力人记录表：一人一助力，只追加；助力入口挂风控黑名单检查防刷
        Schema::create('marketing_assist_helpers', function (Blueprint $table) {
            $table->comment('助力人记录表');
            $table->id();
            $table->unsignedInteger('record_id')->comment('助力参与记录ID');
            $table->unsignedInteger('helper_user_id')->comment('助力人用户ID');
            $table->timestamp('create_time')->useCurrent()->comment('助力时间');
            $table->unique(['record_id', 'helper_user_id'], 'uk_marketing_assist_helpers_record_helper');
            $table->index('helper_user_id', 'idx_marketing_assist_helpers_helper_user_id');
        });

        // 砍价活动表：时段/状态，价格区间在 item 按 SKU 配置
        Schema::create('marketing_bargain_activities', function (Blueprint $table) {
            $table->comment('砍价活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->unsignedInteger('spu_id')->comment('商品SPU ID');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['spu_id', 'status'], 'idx_marketing_bargain_activities_spu_status');
        });

        // 砍价场次商品表：SKU 级价格区间，砍到 floor_price 即可按当前价下单
        Schema::create('marketing_bargain_items', function (Blueprint $table) {
            $table->comment('砍价场次商品表');
            $table->id();
            $table->unsignedInteger('activity_id')->comment('砍价活动ID');
            $table->unsignedInteger('sku_id')->comment('SKU ID');
            $table->decimal('original_price', 10, 2)->comment('起始价(发起时售价口径)');
            $table->decimal('floor_price', 10, 2)->comment('底价(砍到底价即可下单;floor<=original 应用校验)');
            $table->unsignedInteger('max_cut_count')->default(0)->comment('最大帮砍刀数,0=不限,金额收敛到底价');
            $table->json('config')->nullable()->comment('玩法参数(随机递减算法边界/首刀上限等,应用层约定)');
            $table->timestamps();
            $table->unique(['activity_id', 'sku_id'], 'uk_marketing_bargain_items_activity_sku');
            $table->index('sku_id', 'idx_marketing_bargain_items_sku_id');
        });

        // 砍价单表：current_price 条件更新防并发超砍（where current_price - cut >= floor_price）；超时/取消不可下单
        Schema::create('marketing_bargain_records', function (Blueprint $table) {
            $table->comment('砍价单表');
            $table->id();
            $table->string('bargain_no', 32)->comment('砍价单号,全局唯一');
            $table->unsignedInteger('item_id')->comment('砍价场次商品ID');
            $table->unsignedInteger('user_id')->comment('发起人用户ID');
            $table->decimal('current_price', 10, 2)->comment('当前价,逐刀递减且>=floor_price由条件更新保证');
            $table->unsignedInteger('cut_count')->default(0)->comment('已砍刀数');
            $table->tinyInteger('status')->default(1)->comment('状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消');
            $table->dateTime('expire_time')->comment('砍价截止时间,超时扫描依据');
            $table->dateTime('success_time')->nullable()->comment('到底价时间');
            $table->string('order_no', 30)->nullable()->comment('成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束');
            $table->timestamps();
            $table->unique('bargain_no', 'uk_marketing_bargain_records_bargain_no');
            $table->unique('order_no', 'uk_marketing_bargain_records_order_no');
            $table->index(['user_id', 'status'], 'idx_marketing_bargain_records_user_status');
            $table->index(['status', 'expire_time'], 'idx_marketing_bargain_records_status_expire');
        });

        // 帮砍记录表：一人一刀，只追加，cut_amount 与砍价单条件更新同事务；入口挂风控黑名单检查防刷
        Schema::create('marketing_bargain_helpers', function (Blueprint $table) {
            $table->comment('帮砍记录表');
            $table->id();
            $table->unsignedInteger('record_id')->comment('砍价单ID');
            $table->unsignedInteger('helper_user_id')->comment('帮砍人用户ID');
            $table->decimal('cut_amount', 10, 2)->comment('本刀砍掉金额(与record条件更新同事务)');
            $table->timestamp('create_time')->useCurrent()->comment('帮砍时间');
            $table->unique(['record_id', 'helper_user_id'], 'uk_marketing_bargain_helpers_record_helper');
            $table->index('helper_user_id', 'idx_marketing_bargain_helpers_helper_user_id');
        });

        // 分享行为记录表：只追加，分享/海报/口令的行为留痕，订单归因（orders.attributed_user_id）的判定来源
        Schema::create('marketing_share_records', function (Blueprint $table) {
            $table->comment('分享行为记录表');
            $table->id();
            $table->unsignedInteger('sharer_user_id')->comment('分享者用户ID');
            $table->unsignedInteger('spu_id')->nullable()->comment('分享对象商品SPU ID,NULL=整店/页面分享');
            $table->tinyInteger('share_channel')->comment('分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群');
            $table->string('scene_value', 64)->default('')->comment('小程序场景值,落地追踪');
            $table->string('share_code', 16)->default('')->comment('使用的推广码,关联users.share_code');
            $table->timestamp('create_time')->useCurrent()->comment('分享时间,归因窗口起点');
            $table->index(['sharer_user_id', 'create_time'], 'idx_marketing_share_records_sharer_time');
            $table->index(['spu_id', 'create_time'], 'idx_marketing_share_records_spu_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_share_records');
        Schema::dropIfExists('marketing_bargain_helpers');
        Schema::dropIfExists('marketing_bargain_records');
        Schema::dropIfExists('marketing_bargain_items');
        Schema::dropIfExists('marketing_bargain_activities');
        Schema::dropIfExists('marketing_assist_helpers');
        Schema::dropIfExists('marketing_assist_records');
        Schema::dropIfExists('marketing_assist_activities');
        Schema::dropIfExists('marketing_group_buy_team_members');
        Schema::dropIfExists('marketing_group_buy_teams');
        Schema::dropIfExists('marketing_group_buy_items');
        Schema::dropIfExists('marketing_group_buy_activities');
        Schema::dropIfExists('marketing_flash_sale_logs');
        Schema::dropIfExists('marketing_distribution_commissions');
        Schema::dropIfExists('marketing_distribution_relations');
        Schema::dropIfExists('marketing_distributors');
        Schema::dropIfExists('marketing_distribution_commission_rules');
        Schema::dropIfExists('marketing_flash_sale_items');
        Schema::dropIfExists('marketing_flash_sales');
        Schema::dropIfExists('marketing_promotion_ladders');
        Schema::dropIfExists('marketing_promotion_scopes');
        Schema::dropIfExists('marketing_promotions');
        Schema::dropIfExists('marketing_user_coupons');
        Schema::dropIfExists('marketing_coupons');
    }
};
