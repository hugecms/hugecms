<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Http\Controllers\BaseController;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: 'test', tags: ['店铺模块'])]
    public function index()
    {
        return 'welcome shop index';
    }
}
