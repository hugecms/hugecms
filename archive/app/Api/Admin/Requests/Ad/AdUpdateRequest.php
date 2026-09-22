<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdUpdateRequest',
    required: [
        self::getId,
        self::getPositionId,
        self::getTitle,
        self::getAdType,
        self::getCoverImage,
        self::getContent,
        self::getLinkUrl,
        self::getLinkTarget,
        self::getSort,
        self::getStartTime,
        self::getEndTime,
        self::getDisplayLimit,
        self::getClickLimit,
        self::getDisplayCount,
        self::getClickCount,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPositionId, description: '所属广告位ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '广告标题', type: 'string'),
        new OA\Property(property: self::getAdType, description: '广告类型：image/text/video/html', type: 'string'),
        new OA\Property(property: self::getCoverImage, description: '广告图片/视频封面URL', type: 'string'),
        new OA\Property(property: self::getContent, description: '广告内容（纯文本或HTML代码）', type: 'string'),
        new OA\Property(property: self::getLinkUrl, description: '广告跳转链接', type: 'string'),
        new OA\Property(property: self::getLinkTarget, description: '打开方式：0本窗口，1新窗口', type: 'integer'),
        new OA\Property(property: self::getSort, description: '展示排序（数值越小越靠前）', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '投放开始时间（NULL表示立即开始）', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '投放结束时间（NULL表示永久，过期由时间判断）', type: 'string'),
        new OA\Property(property: self::getDisplayLimit, description: '展示次数上限（0不限）', type: 'integer'),
        new OA\Property(property: self::getClickLimit, description: '点击次数上限（0不限）', type: 'integer'),
        new OA\Property(property: self::getDisplayCount, description: '实际展示次数', type: 'integer'),
        new OA\Property(property: self::getClickCount, description: '实际点击次数', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class AdUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPositionId = 'positionId';

    public const string getTitle = 'title';

    public const string getAdType = 'adType';

    public const string getCoverImage = 'coverImage';

    public const string getContent = 'content';

    public const string getLinkUrl = 'linkUrl';

    public const string getLinkTarget = 'linkTarget';

    public const string getSort = 'sort';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getDisplayLimit = 'displayLimit';

    public const string getClickLimit = 'clickLimit';

    public const string getDisplayCount = 'displayCount';

    public const string getClickCount = 'clickCount';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPositionId => 'required',
            self::getTitle => 'required',
            self::getAdType => 'required',
            self::getCoverImage => 'required',
            self::getContent => 'required',
            self::getLinkUrl => 'required',
            self::getLinkTarget => 'required',
            self::getSort => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getDisplayLimit => 'required',
            self::getClickLimit => 'required',
            self::getDisplayCount => 'required',
            self::getClickCount => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPositionId.'.required' => '请设置所属广告位ID',
            self::getTitle.'.required' => '请设置广告标题',
            self::getAdType.'.required' => '请设置广告类型：image/text/video/html',
            self::getCoverImage.'.required' => '请设置广告图片/视频封面URL',
            self::getContent.'.required' => '请设置广告内容（纯文本或HTML代码）',
            self::getLinkUrl.'.required' => '请设置广告跳转链接',
            self::getLinkTarget.'.required' => '请设置打开方式：0本窗口，1新窗口',
            self::getSort.'.required' => '请设置展示排序（数值越小越靠前）',
            self::getStartTime.'.required' => '请设置投放开始时间（NULL表示立即开始）',
            self::getEndTime.'.required' => '请设置投放结束时间（NULL表示永久，过期由时间判断）',
            self::getDisplayLimit.'.required' => '请设置展示次数上限（0不限）',
            self::getClickLimit.'.required' => '请设置点击次数上限（0不限）',
            self::getDisplayCount.'.required' => '请设置实际展示次数',
            self::getClickCount.'.required' => '请设置实际点击次数',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
