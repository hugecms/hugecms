<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Constants\Constant;
use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: Constant::Version, description: Constant::Release, title: '公共平台API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/common/', description: '开发环境')]
abstract class BaseController extends Controller {}
