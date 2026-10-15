<?php

declare(strict_types=1);

namespace app\api\user\controller;

use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Get(path: 'login', summary: '登录', tags: ['登录'])]
    public function login()
    {
        return 'login';
    }
}
