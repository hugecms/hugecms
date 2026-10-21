<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Services\BargainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class BargainController extends BaseController
{
    public function __construct(
        private readonly BargainService $bargainService,
    ) {}

    /**
     * TODO: 用户认证落地后改为 $request->user()->id，当前以请求参数传入
     */
    private function resolveUserId(Request $request): int
    {
        return \intval($request->input('userId', '0'));
    }

    #[OA\Post(path: '/bargain/initiate', summary: '发起砍价', security: [['bearerAuth' => []]], tags: ['砍价'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function initiate(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $recordId = $this->bargainService->initiate($userId, \intval($request->input('itemId', '0')));

            return $this->success(['recordId' => $recordId]);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Post(path: '/bargain/cut', summary: '为好友砍一刀', security: [['bearerAuth' => []]], tags: ['砍价'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function cut(Request $request): JsonResponse
    {
        try {
            $helperUserId = $this->resolveUserId($request);
            if ($helperUserId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $result = $this->bargainService->cut($helperUserId, \intval($request->input('recordId', '0')));

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Get(path: '/bargain/detail', summary: '砍价单详情', security: [['bearerAuth' => []]], tags: ['砍价'])]
    #[OA\Parameter(name: 'recordId', description: '砍价单ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function detail(Request $request): JsonResponse
    {
        try {
            $recordId = \intval($request->query('recordId', '0'));

            return $this->success($this->bargainService->detail($recordId));
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Post(path: '/bargain/order', summary: '砍到低价后下单回填', security: [['bearerAuth' => []]], tags: ['砍价'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function order(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $this->bargainService->markOrdered(
                $userId,
                \intval($request->input('recordId', '0')),
                (string) $request->input('orderNo', ''),
            );

            return $this->success();
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }
}
