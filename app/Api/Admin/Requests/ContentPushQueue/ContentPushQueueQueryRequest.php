<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentPushQueue;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentPushQueueQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '被推送的内容ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：pending/processing/success/failed（执行与重试走 Laravel 队列）', type: 'string'),
    ]
)]
class ContentPushQueueQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
