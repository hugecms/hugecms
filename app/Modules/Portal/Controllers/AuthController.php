<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Modules\User\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    #[OA\Get(path: '/login', summary: '获取接口', tags: ['模块'])]
    public function login(): Renderable
    {
        return view('portal::login');
    }
}
