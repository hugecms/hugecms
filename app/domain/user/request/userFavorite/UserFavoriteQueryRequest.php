<?php

declare(strict_types=1);

namespace app\domain\user\request\userFavorite;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserFavoriteQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserFavoriteQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
