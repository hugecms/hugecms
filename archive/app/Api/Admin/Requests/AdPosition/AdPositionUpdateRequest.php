<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdPositionUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getCode,
        self::getWidth,
        self::getHeight,
        self::getAdType,
        self::getMaxCount,
        self::getDescription,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '广告位名称（如：首页Banner）', type: 'string'),
        new OA\Property(property: self::getCode, description: '广告位代码（如：home_banner，模板调用用）', type: 'string'),
        new OA\Property(property: self::getWidth, description: '建议宽度（像素）', type: 'integer'),
        new OA\Property(property: self::getHeight, description: '建议高度（像素）', type: 'integer'),
        new OA\Property(property: self::getAdType, description: '支持的广告类型：image/text/video/html', type: 'string'),
        new OA\Property(property: self::getMaxCount, description: '该广告位最多展示广告数量', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '广告位描述', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class AdPositionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getCode = 'code';

    public const string getWidth = 'width';

    public const string getHeight = 'height';

    public const string getAdType = 'adType';

    public const string getMaxCount = 'maxCount';

    public const string getDescription = 'description';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getCode => 'required',
            self::getWidth => 'required',
            self::getHeight => 'required',
            self::getAdType => 'required',
            self::getMaxCount => 'required',
            self::getDescription => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置广告位名称（如：首页Banner）',
            self::getCode.'.required' => '请设置广告位代码（如：home_banner，模板调用用）',
            self::getWidth.'.required' => '请设置建议宽度（像素）',
            self::getHeight.'.required' => '请设置建议高度（像素）',
            self::getAdType.'.required' => '请设置支持的广告类型：image/text/video/html',
            self::getMaxCount.'.required' => '请设置该广告位最多展示广告数量',
            self::getDescription.'.required' => '请设置广告位描述',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
