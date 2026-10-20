<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatQrcode;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatQrcodeUpdateRequest',
    required: [
        self::getId,
        self::getAppid,
        self::getSceneStr,
        self::getName,
        self::getType,
        self::getTargetId,
        self::getScanCount,
        self::getRegisterCount,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '二维码所属应用AppID,公众号带参码或小程序码', type: 'string'),
        new OA\Property(property: self::getSceneStr, description: '场景值,同一应用下唯一', type: 'string'),
        new OA\Property(property: self::getName, description: '渠道码名称,如:分销员张三专属码', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:distributor=分销邀请,product=商品推广,channel=渠道投放', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '绑定目标ID,分销码=分销员用户ID,商品码=商品ID', type: 'integer'),
        new OA\Property(property: self::getScanCount, description: '扫码次数', type: 'integer'),
        new OA\Property(property: self::getRegisterCount, description: '扫码注册转化数', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class WechatQrcodeUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getSceneStr = 'sceneStr';

    public const string getName = 'name';

    public const string getType = 'type';

    public const string getTargetId = 'targetId';

    public const string getScanCount = 'scanCount';

    public const string getRegisterCount = 'registerCount';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getAppid => 'required',
            self::getSceneStr => 'required',
            self::getName => 'required',
            self::getType => 'required',
            self::getTargetId => 'required',
            self::getScanCount => 'required',
            self::getRegisterCount => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getAppid.'.required' => '请设置二维码所属应用AppID,公众号带参码或小程序码',
            self::getSceneStr.'.required' => '请设置场景值,同一应用下唯一',
            self::getName.'.required' => '请设置渠道码名称,如:分销员张三专属码',
            self::getType.'.required' => '请设置类型:distributor=分销邀请,product=商品推广,channel=渠道投放',
            self::getTargetId.'.required' => '请设置绑定目标ID,分销码=分销员用户ID,商品码=商品ID',
            self::getScanCount.'.required' => '请设置扫码次数',
            self::getRegisterCount.'.required' => '请设置扫码注册转化数',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
