<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class HomeController extends BaseController
{
    #[OA\Get(path: '/home', summary: '用户主页', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function index(): Renderable
    {
        return view('user::home');
    }
}
