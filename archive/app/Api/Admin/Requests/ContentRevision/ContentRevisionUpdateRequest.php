<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentRevision;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentRevisionUpdateRequest',
    required: [
        self::getId,
        self::getContentId,
        self::getAuthorId,
        self::getRevisionData,
        self::getRemark,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '内容主表ID', type: 'integer'),
        new OA\Property(property: self::getAuthorId, description: '修改人', type: 'integer'),
        new OA\Property(property: self::getRevisionData, description: '修改时的全量数据快照（JSON）', type: 'string'),
        new OA\Property(property: self::getRemark, description: '修改备注', type: 'string'),
    ]
)]
class ContentRevisionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getAuthorId = 'authorId';

    public const string getRevisionData = 'revisionData';

    public const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getContentId => 'required',
            self::getAuthorId => 'required',
            self::getRevisionData => 'required',
            self::getRemark => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getContentId.'.required' => '请设置内容主表ID',
            self::getAuthorId.'.required' => '请设置修改人',
            self::getRevisionData.'.required' => '请设置修改时的全量数据快照（JSON）',
            self::getRemark.'.required' => '请设置修改备注',
        ];
    }
}
