<?php

declare(strict_types=1);

namespace app\modules\admin\controller;

use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: 'test', security: [['bearerAuth' => []]], tags: ['后台模块'])]
    public function index()
    {
        return 'welcome admin index';
    }
}
