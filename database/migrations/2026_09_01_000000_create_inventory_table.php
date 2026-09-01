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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // inventory	实时库存主表	id, sku_id, stock_quantity, frozen_quantity (锁定待支付), available_quantity
        // inventory_log	库存变更流水（用于对账）	sku_id, change_quantity, type (扣减/增加/冻结), order_id, create_time
        // warehouse	仓库信息（多仓支持）	id, name, address, contact
        // warehouse_sku	仓库与 SKU 的库存映射	warehouse_id, sku_id, stock
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
