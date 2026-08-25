<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Post(path: '/login', summary: '获取接口', tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', description: '状态码', type: 'integer', example: 0),
            new OA\Property(property: 'message', description: '消息', type: 'string', example: 'ok'),
            new OA\Property(property: 'data', ref: Object::class),
        ],
    ))]
    public function login(): JsonResponse
    {
        return $this->success([]);
    }
}
