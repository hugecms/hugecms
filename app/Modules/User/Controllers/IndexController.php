<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Http\Controllers\BaseController;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: 'test', tags: ['用户模块'])]
    public function index()
    {
        return 'welcome user index';
    }
}
