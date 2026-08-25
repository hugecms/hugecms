<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '仪表盘页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function index(): Renderable
    {
        return view('admin::dashboard');
    }
}
