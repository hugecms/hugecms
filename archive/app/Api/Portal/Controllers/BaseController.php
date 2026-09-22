<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '门户模块接口', title: '门户模块接口', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/portal/', description: 'Portal模块API')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller
{
    // please fill in your code here
}
