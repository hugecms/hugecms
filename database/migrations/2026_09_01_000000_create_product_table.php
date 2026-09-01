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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // catalog 商品目录表
        // category	商品类目（支持多级树形）	id, name, parent_id, level, sort_order, icon
        // brand	品牌表	id, name, logo, sort_order
        // product	商品 SPU（标准产品单位）	id, category_id, brand_id, name, subtitle, description, main_image
        // sku	商品 SKU（库存量单位）	id, product_id, attrs (JSON规格), price, stock, image, status
        // product_attribute	商品属性定义（如尺寸、颜色）	id, category_id, name, input_type (下拉/手填)
        // product_attribute_value	商品属性值	attribute_id, sku_id, value
        // product_image	商品附图（多图）	product_id, image_url, sort_order
        // product_comment	商品评价	id, user_id, sku_id, order_id, rating, content, images
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
