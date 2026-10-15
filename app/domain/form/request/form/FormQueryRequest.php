<?php

declare(strict_types=1);

namespace app\domain\form\request\form;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'FormQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class FormQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
