<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Dashboard;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DashboardTodoResponse')]
class TodoResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'pendingShipments', description: '待发货订单数', type: 'integer')]
    private int $pendingShipments;

    #[OA\Property(property: 'pendingRefunds', description: '待审核退款单数', type: 'integer')]
    private int $pendingRefunds;

    #[OA\Property(property: 'pendingWithdrawals', description: '待审核提现单数', type: 'integer')]
    private int $pendingWithdrawals;

    #[OA\Property(property: 'lowStock', description: '库存预警 SKU 数', type: 'integer')]
    private int $lowStock;

    #[OA\Property(property: 'pendingRiskAppeals', description: '申诉中风控事件数', type: 'integer')]
    private int $pendingRiskAppeals;

    public function getPendingShipments(): int
    {
        return $this->pendingShipments;
    }

    public function setPendingShipments(int $pendingShipments): void
    {
        $this->pendingShipments = $pendingShipments;
    }

    public function getPendingRefunds(): int
    {
        return $this->pendingRefunds;
    }

    public function setPendingRefunds(int $pendingRefunds): void
    {
        $this->pendingRefunds = $pendingRefunds;
    }

    public function getPendingWithdrawals(): int
    {
        return $this->pendingWithdrawals;
    }

    public function setPendingWithdrawals(int $pendingWithdrawals): void
    {
        $this->pendingWithdrawals = $pendingWithdrawals;
    }

    public function getLowStock(): int
    {
        return $this->lowStock;
    }

    public function setLowStock(int $lowStock): void
    {
        $this->lowStock = $lowStock;
    }

    public function getPendingRiskAppeals(): int
    {
        return $this->pendingRiskAppeals;
    }

    public function setPendingRiskAppeals(int $pendingRiskAppeals): void
    {
        $this->pendingRiskAppeals = $pendingRiskAppeals;
    }
}
