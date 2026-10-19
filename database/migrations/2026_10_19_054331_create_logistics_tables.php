<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 物流域：配送方式、配送区域（含运价）、区域-行政区划关联、物流轨迹
     */
    public function up(): void
    {
        // 配送方式表
        Schema::create('shippings', function (Blueprint $table) {
            $table->comment('配送方式表');
            $table->id();
            $table->string('name', 50)->comment('配送方式名称,如:顺丰标快');
            $table->string('code', 50)->comment('承运商/运费模板标识');
            $table->string('description', 255)->nullable()->comment('配送说明');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        // 配送区域表：一个配送方式按区域差异化定价
        Schema::create('shipping_areas', function (Blueprint $table) {
            $table->comment('配送区域表');
            $table->id();
            $table->unsignedInteger('shipping_id')->comment('配送方式ID');
            $table->string('name', 50)->comment('区域名称,如:华东地区');
            $table->json('fee_config')->comment('运价配置(JSON):首重/续重/首费/续费/固定运费等');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index('shipping_id', 'idx_shipping_areas_shipping_id');
        });

        // 配送区域-行政区划关联表：区域命中的省市区，未命中的走默认运费
        Schema::create('shipping_area_regions', function (Blueprint $table) {
            $table->comment('配送区域行政区划关联表');
            $table->unsignedInteger('shipping_area_id')->comment('配送区域ID');
            $table->unsignedInteger('region_id')->comment('行政区划ID');
            $table->primary(['shipping_area_id', 'region_id']);
            $table->index('region_id', 'idx_shipping_area_regions_region_id');
        });

        // 物流轨迹表：第三方轨迹查询结果落缓存，同步去重由应用层按(shipment_id,accept_time,description)比对
        Schema::create('shipping_shipment_tracks', function (Blueprint $table) {
            $table->comment('物流轨迹表');
            $table->id();
            $table->unsignedInteger('shipment_id')->comment('发货单ID');
            $table->dateTime('accept_time')->comment('轨迹发生时间');
            $table->string('location', 100)->nullable()->comment('所在城市/网点');
            $table->string('description', 255)->comment('轨迹描述,如:已签收');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['shipment_id', 'accept_time'], 'idx_shipment_tracks_shipment_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_shipment_tracks');
        Schema::dropIfExists('shipping_area_regions');
        Schema::dropIfExists('shipping_areas');
        Schema::dropIfExists('shippings');
    }
};
