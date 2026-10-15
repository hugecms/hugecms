<?php

declare(strict_types=1);

namespace app\api\common\controller;

use Juling\Foundation\Response\JsonResponse;
use Juling\Foundation\Routing\Controller;
use OpenApi\Attributes as OA;

class CaptchaController extends Controller
{
    use JsonResponse;

    #[OA\Get(path: 'captcha', summary: '获取验证码图片', tags: ['验证码'])]
    public function index()
    {
        return 'captcha';
    }
}
