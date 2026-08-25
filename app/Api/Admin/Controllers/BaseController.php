<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '管理后台系统API', title: '管理后台系统API', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/admin/', description: 'Admin业务API')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller {}
