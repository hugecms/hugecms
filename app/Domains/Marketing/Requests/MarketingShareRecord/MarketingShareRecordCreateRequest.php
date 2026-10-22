<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingShareRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingShareRecordCreateRequest',
    required: [
        self::getSharerUserId,
        self::getSpuId,
        self::getShareChannel,
        self::getSceneValue,
        self::getShareCode,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getSharerUserId, description: '分享者用户ID', type: 'integer'),
        new OA\Property(property: self::getSpuId, description: '分享对象商品SPU ID,NULL=整店/页面分享', type: 'integer'),
        new OA\Property(property: self::getShareChannel, description: '分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群', type: 'integer'),
        new OA\Property(property: self::getSceneValue, description: '小程序场景值,落地追踪', type: 'string'),
        new OA\Property(property: self::getShareCode, description: '使用的推广码,关联users.share_code', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '分享时间,归因窗口起点', type: 'string'),
    ]
)]
class MarketingShareRecordCreateRequest extends FormRequest
{
    public const string getSharerUserId = 'sharerUserId';

    public const string getSpuId = 'spuId';

    public const string getShareChannel = 'shareChannel';

    public const string getSceneValue = 'sceneValue';

    public const string getShareCode = 'shareCode';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getSharerUserId => 'required',
            self::getSpuId => 'required',
            self::getShareChannel => 'required',
            self::getSceneValue => 'required',
            self::getShareCode => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getSharerUserId.'.required' => '请设置分享者用户ID',
            self::getSpuId.'.required' => '请设置分享对象商品SPU ID,NULL=整店/页面分享',
            self::getShareChannel.'.required' => '请设置分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群',
            self::getSceneValue.'.required' => '请设置小程序场景值,落地追踪',
            self::getShareCode.'.required' => '请设置使用的推广码,关联users.share_code',
            self::getCreateTime.'.required' => '请设置分享时间,归因窗口起点',
        ];
    }
}
