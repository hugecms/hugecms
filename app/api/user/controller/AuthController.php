<?php

declare(strict_types=1);

namespace app\api\user\controller;

use Juling\Foundation\Response\JsonResponse;
use Juling\Foundation\Routing\Controller;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    use JsonResponse;

    #[OA\Get(path: 'login', summary: '登录', tags: ['登录'])]
    public function login()
    {
        return 'login';
    }
}
