<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '获取接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function index(): Renderable
    {
        return view('admin::index');
    }
}
