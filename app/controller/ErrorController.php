<?php

declare(strict_types=1);

namespace app\controller;

class ErrorController extends BaseController
{
    public function __call($method, $args)
    {
        return 'error request!';
    }
}
