<?php

declare(strict_types=1);

namespace App\Domains\Email\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Email\Entities\EmailSubscriptionEntity;
use App\Domains\Email\Requests\EmailSubscription\EmailSubscriptionCreateRequest;
use App\Domains\Email\Requests\EmailSubscription\EmailSubscriptionDestroyRequest;
use App\Domains\Email\Requests\EmailSubscription\EmailSubscriptionQueryRequest;
use App\Domains\Email\Requests\EmailSubscription\EmailSubscriptionUpdateRequest;
use App\Domains\Email\Responses\EmailSubscription\EmailSubscriptionDestroyResponse;
use App\Domains\Email\Responses\EmailSubscription\EmailSubscriptionQueryResponse;
use App\Domains\Email\Responses\EmailSubscription\EmailSubscriptionResponse;
use App\Domains\Email\Services\EmailSubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class EmailSubscriptionController extends BaseController
{
    public function __construct(
        private readonly EmailSubscriptionService $emailSubscriptionService,
    ) {}

    #[OA\Post(path: '/emailSubscription/search', summary: '查询邮件订阅列表接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriptionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: EmailSubscriptionQueryResponse::class),
        ],
    ))]
    public function search(EmailSubscriptionQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[EmailSubscriptionQueryRequest::getId])) {
                $condition[] = [EmailSubscriptionEntity::getId, '=', $requestData[EmailSubscriptionQueryRequest::getId]];
            }
            if (isset($requestData[EmailSubscriptionQueryRequest::getEmail])) {
                $condition[] = [EmailSubscriptionEntity::getEmail, '=', $requestData[EmailSubscriptionQueryRequest::getEmail]];
            }

            $result = $this->emailSubscriptionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = EmailSubscriptionResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = EmailSubscriptionQueryResponse::from($result);
            $response->setFirstPageUrl('');
            $response->setLastPageUrl('');
            $response->setLinks([]);
            $response->setPath('');

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/emailSubscription/store', summary: '新增邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriptionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: EmailSubscriptionResponse::class),
        ],
    ))]
    public function store(EmailSubscriptionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = EmailSubscriptionEntity::from($requestData);

            if ($this->emailSubscriptionService->save($input->toEntity())) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/emailSubscription/show', summary: '获取邮件订阅详情接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: EmailSubscriptionResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $emailSubscription = $this->emailSubscriptionService->getOneById($id);
            if (empty($emailSubscription)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = EmailSubscriptionResponse::from($emailSubscription);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/emailSubscription/update', summary: '更新邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriptionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: EmailSubscriptionResponse::class),
        ],
    ))]
    public function update(EmailSubscriptionUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $emailSubscription = $this->emailSubscriptionService->getOneById($id);
            if (empty($emailSubscription)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = EmailSubscriptionEntity::from($requestData);

            $this->emailSubscriptionService->updateById($input->toEntity(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Post(path: '/emailSubscription/destroy', summary: '删除邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriptionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: EmailSubscriptionDestroyResponse::class),
        ],
    ))]
    public function destroy(EmailSubscriptionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->emailSubscriptionService->removeByIds($requestData['ids'])) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DESTROY_ERROR);
        }
    }
}
