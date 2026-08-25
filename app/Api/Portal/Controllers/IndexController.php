<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '获取接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', description: '状态码', type: 'integer', example: 0),
            new OA\Property(property: 'message', description: '消息', type: 'string', example: 'ok'),
            new OA\Property(property: 'data', ref: Object::class),
        ],
    ))]
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
