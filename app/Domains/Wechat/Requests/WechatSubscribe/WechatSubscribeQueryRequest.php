<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatSubscribe;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatSubscribeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '授权所在小程序AppID', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getTemplateId, description: '模板ID,关联wechat_templates', type: 'integer'),
    ]
)]
class WechatSubscribeQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getUserId = 'userId';

    public const string getTemplateId = 'templateId';

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
