<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 网站域：广告位定义、轮播图/广告、友情链接、导航、搜索热词、每日统计汇总
     */
    public function up(): void
    {
        // 广告位定义表：site_banners.position 关联本表 code，消除魔法字符串，后台投放时下拉选择
        Schema::create('site_ad_positions', function (Blueprint $table) {
            $table->comment('广告位定义表');
            $table->id();
            $table->string('code', 50)->comment('广告位标识,如:home_top');
            $table->string('name', 50)->comment('广告位名称');
            $table->string('description', 255)->nullable()->comment('说明,如:首页顶部,建议尺寸750x300');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('code', 'uk_site_ad_positions_code');
        });

        // 轮播图/广告表：按广告位标识分组展示
        Schema::create('site_banners', function (Blueprint $table) {
            $table->comment('轮播图/广告表');
            $table->id();
            $table->string('position', 50)->comment('广告位标识,如:home_top');
            $table->string('title', 100)->comment('标题');
            $table->string('image', 255)->comment('图片地址');
            $table->string('link', 255)->nullable()->comment('跳转链接');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->dateTime('start_time')->nullable()->comment('投放开始时间');
            $table->dateTime('end_time')->nullable()->comment('投放结束时间');
            $table->timestamps();
            $table->index('position', 'idx_site_banners_position');
        });

        // 友情链接表
        Schema::create('site_links', function (Blueprint $table) {
            $table->comment('友情链接表');
            $table->id();
            $table->string('name', 50)->comment('站点名称');
            $table->string('url', 255)->comment('链接地址');
            $table->string('logo', 255)->nullable()->comment('站点LOGO');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
        });

        // 导航表：头部/底部菜单，树形结构
        Schema::create('site_navs', function (Blueprint $table) {
            $table->comment('导航表');
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID,0=顶级');
            $table->string('name', 50)->comment('导航名称');
            $table->string('url', 255)->comment('链接地址');
            $table->string('position', 20)->default('header')->comment('展示位置:header=头部,footer=底部');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=隐藏,1=显示');
            $table->timestamps();
            $table->index('position', 'idx_site_navs_position');
        });

        // 搜索热词表：用户每次搜索按词 upsert 计数，配合 Elasticsearch；is_hot 为人工推荐位
        Schema::create('site_search_keywords', function (Blueprint $table) {
            $table->comment('搜索热词表');
            $table->id();
            $table->string('keyword', 100)->comment('搜索词');
            $table->unsignedInteger('search_count')->default(0)->comment('搜索次数');
            $table->unsignedInteger('result_count')->default(0)->comment('最近一次结果数,0=无结果');
            $table->timestamp('last_searched_at')->nullable()->comment('最后搜索时间');
            $table->tinyInteger('is_hot')->default(0)->comment('人工推荐热词:0=否,1=是');
            $table->tinyInteger('status')->default(1)->comment('状态:0=屏蔽,1=正常');
            $table->timestamps();
            $table->unique('keyword', 'uk_site_search_keywords_keyword');
            $table->index('search_count', 'idx_site_search_keywords_search_count');
        });

        // 每日统计汇总表：定时任务凌晨聚合前一天数据，一天一行，报表/运营看板数据源
        // 明细以 orders/payments/user_account_logs 为准，本表仅为查询加速的汇总层
        Schema::create('site_statistics_daily', function (Blueprint $table) {
            $table->comment('每日统计汇总表');
            $table->id();
            $table->date('stat_date')->comment('统计日期');
            $table->unsignedInteger('pv')->default(0)->comment('页面浏览量');
            $table->unsignedInteger('uv')->default(0)->comment('独立访客数');
            $table->unsignedInteger('new_users')->default(0)->comment('新增注册用户数');
            $table->unsignedInteger('order_count')->default(0)->comment('下单笔数');
            $table->decimal('order_amount', 10, 2)->default(0)->comment('下单金额');
            $table->unsignedInteger('pay_count')->default(0)->comment('支付笔数');
            $table->decimal('pay_amount', 10, 2)->default(0)->comment('支付金额');
            $table->unsignedInteger('refund_count')->default(0)->comment('退款笔数');
            $table->decimal('refund_amount', 10, 2)->default(0)->comment('退款金额');
            $table->timestamps();
            $table->unique('stat_date', 'uk_site_statistics_daily_stat_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_statistics_daily');
        Schema::dropIfExists('site_search_keywords');
        Schema::dropIfExists('site_navs');
        Schema::dropIfExists('site_links');
        Schema::dropIfExists('site_banners');
        Schema::dropIfExists('site_ad_positions');
    }
};
