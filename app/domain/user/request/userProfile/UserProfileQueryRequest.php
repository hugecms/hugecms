<?php

declare(strict_types=1);

namespace app\domain\user\request\userProfile;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserProfileQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserProfileQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
