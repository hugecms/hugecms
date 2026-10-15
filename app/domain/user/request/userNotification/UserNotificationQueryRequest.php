<?php

declare(strict_types=1);

namespace app\domain\user\request\userNotification;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserNotificationQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserNotificationQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
