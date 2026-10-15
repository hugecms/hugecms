<?php

declare(strict_types=1);

namespace app\domain\site\request\siteLink;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SiteLinkQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SiteLinkQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
