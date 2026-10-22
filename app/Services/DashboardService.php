<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * 管理端仪表盘/待办聚合：跨域只读计数
 * 口径：待发货=已支付待发货订单；待售后=待审核退款单；待提现=待审核提现单；
 * 库存预警=启用 SKU 且设置预警线且 库存<=预警线；待处理风控=申诉中事件
 */
class DashboardService
{
    /**
     * @return array{pendingShipments: int, pendingRefunds: int, pendingWithdrawals: int, lowStock: int, pendingRiskAppeals: int}
     */
    public function todoCounts(): array
    {
        return [
            'pendingShipments' => (int) DB::table('orders')
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->count(),
            'pendingRefunds' => (int) DB::table('order_refunds')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->count(),
            'pendingWithdrawals' => (int) DB::table('user_withdraw_orders')
                ->where('status', 10)
                ->whereNull('deleted_at')
                ->count(),
            'lowStock' => (int) DB::table('product_skus')
                ->where('status', 1)
                ->where('warn_stock', '>', 0)
                ->whereColumn('stock', '<=', 'warn_stock')
                ->whereNull('deleted_at')
                ->count(),
            'pendingRiskAppeals' => (int) DB::table('system_risk_records')
                ->where('appeal_status', 1)
                ->count(),
        ];
    }
}
