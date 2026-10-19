<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class HomeController extends Controller
{
    #[OA\Get(path: 'home', summary: '首页', tags: ['首页'])]
    public function index()
    {
        return 'home';
    }
}
