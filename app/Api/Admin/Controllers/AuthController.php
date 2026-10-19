<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Get(path: 'login', summary: '登录', tags: ['登录'])]
    public function login()
    {
        return 'login';
    }
}
