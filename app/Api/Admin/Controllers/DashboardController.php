<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Responses\Dashboard\TodoResponse;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class DashboardController extends BaseController
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    #[OA\Get(path: '/dashboard/todo', summary: '获取管理端待办计数', security: [['bearerAuth' => []]], tags: ['仪表盘模块'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TodoResponse::class))]
    public function todo(): JsonResponse
    {
        try {
            $counts = $this->dashboardService->todoCounts();

            $response = new TodoResponse;
            $response->setPendingShipments($counts['pendingShipments']);
            $response->setPendingRefunds($counts['pendingRefunds']);
            $response->setPendingWithdrawals($counts['pendingWithdrawals']);
            $response->setLowStock($counts['lowStock']);
            $response->setPendingRiskAppeals($counts['pendingRiskAppeals']);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->error('获取待办计数失败');
        }
    }
}
