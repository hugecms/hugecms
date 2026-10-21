<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Services\AssistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AssistController extends BaseController
{
    public function __construct(
        private readonly AssistService $assistService,
    ) {}

    /**
     * TODO: 用户认证落地后改为 $request->user()->id，当前以请求参数传入
     */
    private function resolveUserId(Request $request): int
    {
        return \intval($request->input('userId', '0'));
    }

    #[OA\Post(path: '/assist/initiate', summary: '发起助力', security: [['bearerAuth' => []]], tags: ['助力'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function initiate(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $recordId = $this->assistService->initiate($userId, \intval($request->input('activityId', '0')));

            return $this->success(['recordId' => $recordId]);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Post(path: '/assist/help', summary: '为好友助力', security: [['bearerAuth' => []]], tags: ['助力'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function help(Request $request): JsonResponse
    {
        try {
            $helperUserId = $this->resolveUserId($request);
            if ($helperUserId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $this->assistService->assist($helperUserId, \intval($request->input('recordId', '0')));

            return $this->success();
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Get(path: '/assist/record', summary: '我的助力进度', security: [['bearerAuth' => []]], tags: ['助力'])]
    #[OA\Parameter(name: 'activityId', description: '活动ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function record(Request $request): JsonResponse
    {
        try {
            $userId = $this->resolveUserId($request);
            if ($userId <= 0) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            return $this->success($this->assistService->myRecord(
                $userId,
                \intval($request->query('activityId', '0')),
            ));
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }
}
