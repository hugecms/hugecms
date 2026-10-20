<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTopicItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTopicItemCreateRequest',
    required: [
        self::getTopicId,
        self::getTargetType,
        self::getTargetId,
        self::getSort,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getTopicId, description: '专题ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class ContentTopicItemCreateRequest extends FormRequest
{
    public const string getTopicId = 'topicId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getSort = 'sort';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getTopicId => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getSort => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTopicId.'.required' => '请设置专题ID',
            self::getTargetType.'.required' => '请设置目标类型:article,product',
            self::getTargetId.'.required' => '请设置目标ID',
            self::getSort.'.required' => '请设置排序',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
