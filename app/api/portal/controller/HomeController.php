<?php

declare(strict_types=1);

namespace app\api\portal\controller;

use OpenApi\Attributes as OA;

class HomeController extends BaseController
{
    #[OA\Get(path: 'home', summary: '首页', tags: ['首页'])]
    public function index()
    {
        return 'home';
    }
}
