<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentChannel;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentChannelUpdateRequest',
    required: [
        self::getId,
        self::getCode,
        self::getAppid,
        self::getName,
        self::getConfig,
        self::getStatus,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '渠道标识:wechat=微信支付,alipay=支付宝,balance=余额', type: 'string'),
        new OA\Property(property: self::getAppid, description: '应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号', type: 'string'),
        new OA\Property(property: self::getName, description: '渠道名称', type: 'string'),
        new OA\Property(property: self::getConfig, description: '渠道配置(JSON):商户号/密钥等', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
    ]
)]
class PaymentChannelUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

    public const string getAppid = 'appid';

    public const string getName = 'name';

    public const string getConfig = 'config';

    public const string getStatus = 'status';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCode => 'required',
            self::getAppid => 'required',
            self::getName => 'required',
            self::getConfig => 'required',
            self::getStatus => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCode.'.required' => '请设置渠道标识:wechat=微信支付,alipay=支付宝,balance=余额',
            self::getAppid.'.required' => '请设置应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号',
            self::getName.'.required' => '请设置渠道名称',
            self::getConfig.'.required' => '请设置渠道配置(JSON):商户号/密钥等',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
            self::getSort.'.required' => '请设置排序',
        ];
    }
}
