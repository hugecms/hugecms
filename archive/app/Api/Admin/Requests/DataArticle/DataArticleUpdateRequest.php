<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\DataArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'DataArticleUpdateRequest',
    required: [
        self::getId,
        self::getContentId,
        self::getField1,
        self::getField2,
        self::getField3,
        self::getExtra,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '关联内容主表ID（一对一）', type: 'integer'),
        new OA\Property(property: self::getField1, description: '文章摘要（text）', type: 'string'),
        new OA\Property(property: self::getField2, description: '正文内容（rich_text）', type: 'string'),
        new OA\Property(property: self::getField3, description: '封面图，存附件ID或URL（image）', type: 'string'),
        new OA\Property(property: self::getExtra, description: '预留JSON扩展字段（未建模数据兜底）', type: 'string'),
    ]
)]
class DataArticleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getField1 = 'field1';

    public const string getField2 = 'field2';

    public const string getField3 = 'field3';

    public const string getExtra = 'extra';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer',
            self::getContentId => 'required|integer',
            self::getField1 => 'nullable|string|max:500',
            self::getField2 => 'nullable|string',
            self::getField3 => 'nullable|string|max:255',
            self::getExtra => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getContentId.'.required' => '请设置关联内容主表ID（一对一）',
            self::getField1.'.required' => '请设置文章摘要（text）',
            self::getField2.'.required' => '请设置正文内容（rich_text）',
            self::getField3.'.required' => '请设置封面图，存附件ID或URL（image）',
            self::getExtra.'.required' => '请设置预留JSON扩展字段（未建模数据兜底）',
        ];
    }
}
