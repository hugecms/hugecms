<?php

declare(strict_types=1);

namespace app\domain\site\request\siteBanner;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SiteBannerQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SiteBannerQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
