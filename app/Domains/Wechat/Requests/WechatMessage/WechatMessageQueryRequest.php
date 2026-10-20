<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatMessage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatMessageQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '目标用户ID', type: 'integer'),
        new OA\Property(property: self::getTemplateId, description: '模板ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待发送,1=成功,2=失败', type: 'integer'),
    ]
)]
class WechatMessageQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getTemplateId = 'templateId';

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
