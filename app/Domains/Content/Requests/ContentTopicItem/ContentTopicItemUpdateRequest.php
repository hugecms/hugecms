<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTopicItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTopicItemUpdateRequest',
    required: [
        self::getId,
        self::getTopicId,
        self::getTargetType,
        self::getTargetId,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTopicId, description: '专题ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article=文章,product=商品', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
    ]
)]
class ContentTopicItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTopicId = 'topicId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getTopicId => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getTopicId.'.required' => '请设置专题ID',
            self::getTargetType.'.required' => '请设置目标类型:article=文章,product=商品',
            self::getTargetId.'.required' => '请设置目标ID',
            self::getSort.'.required' => '请设置排序',
        ];
    }
}
