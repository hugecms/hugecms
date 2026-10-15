<?php

declare(strict_types=1);

namespace app\domain\user\request\userSmsCode;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserSmsCodeQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserSmsCodeQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
