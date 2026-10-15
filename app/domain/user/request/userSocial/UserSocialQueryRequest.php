<?php

declare(strict_types=1);

namespace app\domain\user\request\userSocial;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserSocialQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserSocialQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
