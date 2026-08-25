<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '门户API', title: '门户API', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/portal/', description: '门户业务API')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller {}
