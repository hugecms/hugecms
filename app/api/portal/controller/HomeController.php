<?php

declare(strict_types=1);

namespace app\api\portal\controller;

use Juling\Foundation\Response\JsonResponse;
use Juling\Foundation\Routing\Controller;
use OpenApi\Attributes as OA;

class HomeController extends Controller
{
    use JsonResponse;

    #[OA\Get(path: 'home', summary: '首页', tags: ['首页'])]
    public function index()
    {
        return 'home';
    }
}
