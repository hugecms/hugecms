<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteBanner;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteBannerUpdateRequest',
    required: [
        self::getId,
        self::getPosition,
        self::getTitle,
        self::getImage,
        self::getLink,
        self::getDescription,
        self::getSort,
        self::getStatus,
        self::getStartTime,
        self::getEndTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPosition, description: '广告位标识,如:home_top', type: 'string'),
        new OA\Property(property: self::getTitle, description: '标题', type: 'string'),
        new OA\Property(property: self::getImage, description: '图片地址', type: 'string'),
        new OA\Property(property: self::getLink, description: '跳转链接', type: 'string'),
        new OA\Property(property: self::getDescription, description: '描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '投放开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '投放结束时间', type: 'string'),
    ]
)]
class SiteBannerUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPosition = 'position';

    public const string getTitle = 'title';

    public const string getImage = 'image';

    public const string getLink = 'link';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPosition => 'required',
            self::getTitle => 'required',
            self::getImage => 'required',
            self::getLink => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPosition.'.required' => '请设置广告位标识,如:home_top',
            self::getTitle.'.required' => '请设置标题',
            self::getImage.'.required' => '请设置图片地址',
            self::getLink.'.required' => '请设置跳转链接',
            self::getDescription.'.required' => '请设置描述',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
            self::getStartTime.'.required' => '请设置投放开始时间',
            self::getEndTime.'.required' => '请设置投放结束时间',
        ];
    }
}
