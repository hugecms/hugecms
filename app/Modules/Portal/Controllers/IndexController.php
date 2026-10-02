<?php

namespace App\Modules\Portal\Controllers;

use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: 'Portal Index')]
    public function index()
    {
        return view('portal::index');
    }
}
