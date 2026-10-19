<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class CaptchaController extends Controller
{
    #[OA\Get(path: 'captcha', summary: '获取验证码图片', tags: ['验证码'])]
    public function index()
    {
        return 'captcha';
    }
}
