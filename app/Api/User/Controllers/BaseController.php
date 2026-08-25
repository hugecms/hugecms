<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '用户API', title: '用户API', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/user/', description: '用户业务API')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller {}
