<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '公共API', title: '公共API', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/common/', description: '公共业务API')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller {}
