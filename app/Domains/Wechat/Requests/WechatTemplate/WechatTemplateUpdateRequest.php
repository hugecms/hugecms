<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatTemplateUpdateRequest',
    required: [
        self::getId,
        self::getAppid,
        self::getScene,
        self::getType,
        self::getTemplateId,
        self::getName,
        self::getFields,
        self::getPage,
        self::getSendCount,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '所属应用AppID,公众号或小程序', type: 'string'),
        new OA\Property(property: self::getScene, description: '业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等', type: 'string'),
        new OA\Property(property: self::getType, description: '模板类型:1=公众号模板消息,2=小程序订阅消息', type: 'integer'),
        new OA\Property(property: self::getTemplateId, description: '微信模板ID', type: 'string'),
        new OA\Property(property: self::getName, description: '模板名称,运营识别用', type: 'string'),
        new OA\Property(property: self::getFields, description: '字段映射(JSON):业务字段到模板参数的映射', type: 'string'),
        new OA\Property(property: self::getPage, description: '点击跳转页面路径,如:pages/order/detail?id=1', type: 'string'),
        new OA\Property(property: self::getSendCount, description: '发送次数', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class WechatTemplateUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getScene = 'scene';

    public const string getType = 'type';

    public const string getTemplateId = 'templateId';

    public const string getName = 'name';

    public const string getFields = 'fields';

    public const string getPage = 'page';

    public const string getSendCount = 'sendCount';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getAppid => 'required',
            self::getScene => 'required',
            self::getType => 'required',
            self::getTemplateId => 'required',
            self::getName => 'required',
            self::getFields => 'required',
            self::getPage => 'required',
            self::getSendCount => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getAppid.'.required' => '请设置所属应用AppID,公众号或小程序',
            self::getScene.'.required' => '请设置业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等',
            self::getType.'.required' => '请设置模板类型:1=公众号模板消息,2=小程序订阅消息',
            self::getTemplateId.'.required' => '请设置微信模板ID',
            self::getName.'.required' => '请设置模板名称,运营识别用',
            self::getFields.'.required' => '请设置字段映射(JSON):业务字段到模板参数的映射',
            self::getPage.'.required' => '请设置点击跳转页面路径,如:pages/order/detail?id=1',
            self::getSendCount.'.required' => '请设置发送次数',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
