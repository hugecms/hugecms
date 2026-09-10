<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Entities\AttachmentEntity;
use App\Services\AttachmentService;
use App\Api\Admin\Requests\Attachment\AttachmentCreateRequest;
use App\Api\Admin\Requests\Attachment\AttachmentDestroyRequest;
use App\Api\Admin\Requests\Attachment\AttachmentQueryRequest;
use App\Api\Admin\Requests\Attachment\AttachmentUpdateRequest;
use App\Api\Admin\Responses\Attachment\AttachmentDestroyResponse;
use App\Api\Admin\Responses\Attachment\AttachmentQueryResponse;
use App\Api\Admin\Responses\Attachment\AttachmentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AttachmentController extends BaseController
{
    public function __construct(
        private readonly AttachmentService $attachmentService,
    ) {}

    /**
     * 附件上传（multipart/form-data，字段名 file）。
     * 落 public 磁盘（需 php artisan storage:link）；
     * 云存储驱动化（oss/cos/s3）待接入 options.storage_config。
     */
    #[OA\Post(path: '/attachment/upload', summary: '上传附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\RequestBody(required: true, content: new OA\MediaType(mediaType: 'multipart/form-data',
        schema: new OA\Schema(properties: [new OA\Property(property: 'file', type: 'string', format: 'binary')])))]
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('uploads/' . now()->format('Y/m'), 'public');

            [$width, $height] = $this->imageSize($path);

            $input = AttachmentEntity::from([
                'uploaderId' => $request->user()?->getAuthIdentifier() ?? 0,
                'fileName' => $file->getClientOriginalName(),
                'filePath' => $path,
                'storageDriver' => 'local',
                'storageBucket' => '',
                'cdnUrl' => '',
                'fileSize' => $file->getSize() ?? 0,
                'mimeType' => $file->getMimeType() ?? 'application/octet-stream',
                'width' => $width,
                'height' => $height,
                'altText' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'sort' => 0,
            ]);

            $result = $this->attachmentService->save($input->toEntity());
            if (!$result) {
                throw new BusinessException(BusinessEnum::CREATE_FAIL);
            }

            return $this->success([
                'id' => \is_int($result) ? $result : 0,
                'path' => $path,
                'url' => asset('storage/' . $path),
            ]);
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    /**
     * 读取图片尺寸（非图片或失败返回 0×0）。
     *
     * @return array{0: int, 1: int}
     */
    private function imageSize(string $path): array
    {
        try {
            $info = @\getimagesize(\storage_path('app/public/' . $path));
            if (\is_array($info)) {
                return [(int) $info[0], (int) $info[1]];
            }
        } catch (Throwable) {
        }

        return [0, 0];
    }

    #[OA\Post(path: '/attachment/search', summary: '查询附件列表接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentQueryResponse::class),
        ],
    ))]
    public function search(AttachmentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AttachmentQueryRequest::getMimeType])) {
                $condition[] = [AttachmentEntity::getMimeType, '=', $requestData[AttachmentQueryRequest::getMimeType]];
            }
            if (isset($requestData[AttachmentQueryRequest::getUploaderId])) {
                $condition[] = [AttachmentEntity::getUploaderId, '=', $requestData[AttachmentQueryRequest::getUploaderId]];
            }
            if (isset($requestData[AttachmentQueryRequest::getId])) {
                $condition[] = [AttachmentEntity::getId, '=', $requestData[AttachmentQueryRequest::getId]];
            }
            
            $result = $this->attachmentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = AttachmentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = AttachmentQueryResponse::from($result);
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

    #[OA\Post(path: '/attachment/store', summary: '新增附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentResponse::class),
        ],
    ))]
    public function store(AttachmentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = AttachmentEntity::from($requestData);

            if ($this->attachmentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/attachment/show', summary: '获取附件详情接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $attachment = $this->attachmentService->getOneById($id);
            if (empty($attachment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = AttachmentResponse::from($attachment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/attachment/update', summary: '更新附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentResponse::class),
        ],
    ))]
    public function update(AttachmentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $attachment = $this->attachmentService->getOneById($id);
            if (empty($attachment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = AttachmentEntity::from($requestData);

            $this->attachmentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/attachment/destroy', summary: '删除附件接口', security: [['bearerAuth' => []]], tags: ['附件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AttachmentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: AttachmentDestroyResponse::class),
        ],
    ))]
    public function destroy(AttachmentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->attachmentService->removeByIds($requestData['ids'])) {
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
