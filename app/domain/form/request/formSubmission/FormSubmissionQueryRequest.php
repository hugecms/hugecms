<?php

declare(strict_types=1);

namespace app\domain\form\request\formSubmission;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'FormSubmissionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class FormSubmissionQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
