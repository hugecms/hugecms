<?php

declare(strict_types=1);

namespace app\domain\system\request\systemSetting;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemSettingQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemSettingQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
