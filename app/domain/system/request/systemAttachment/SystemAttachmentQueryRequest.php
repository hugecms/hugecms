<?php

declare(strict_types=1);

namespace app\domain\system\request\systemAttachment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemAttachmentQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemAttachmentQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
