<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatSubscribe;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatSubscribeCreateRequest',
    required: [
        self::getAppid,
        self::getUserId,
        self::getTemplateId,
        self::getTimes,
        self::getRemain,
        self::getLastSubscribedAt,
    ],
    properties: [
        new OA\Property(property: self::getAppid, description: '授权所在小程序AppID', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getTemplateId, description: '模板ID,关联wechat_templates', type: 'integer'),
        new OA\Property(property: self::getTimes, description: '累计授权次数', type: 'integer'),
        new OA\Property(property: self::getRemain, description: '剩余可发次数,发送成功后扣减', type: 'integer'),
        new OA\Property(property: self::getLastSubscribedAt, description: '最近授权时间', type: 'string'),
    ]
)]
class WechatSubscribeCreateRequest extends FormRequest
{
    public const string getAppid = 'appid';

    public const string getUserId = 'userId';

    public const string getTemplateId = 'templateId';

    public const string getTimes = 'times';

    public const string getRemain = 'remain';

    public const string getLastSubscribedAt = 'lastSubscribedAt';

    public function rules(): array
    {
        return [
            self::getAppid => 'required',
            self::getUserId => 'required',
            self::getTemplateId => 'required',
            self::getTimes => 'required',
            self::getRemain => 'required',
            self::getLastSubscribedAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAppid.'.required' => '请设置授权所在小程序AppID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getTemplateId.'.required' => '请设置模板ID,关联wechat_templates',
            self::getTimes.'.required' => '请设置累计授权次数',
            self::getRemain.'.required' => '请设置剩余可发次数,发送成功后扣减',
            self::getLastSubscribedAt.'.required' => '请设置最近授权时间',
        ];
    }
}
