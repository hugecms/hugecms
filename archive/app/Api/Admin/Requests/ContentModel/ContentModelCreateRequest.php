<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentModel;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentModelCreateRequest',
    required: [
        self::getName,
        self::getAlias,
        self::getTableName,
        self::getDescription,
        self::getIsSystem,
        self::getIsCommentable,
        self::getStatus,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '模型名称（显示用，如：招聘信息）', type: 'string'),
        new OA\Property(property: self::getAlias, description: '模型别名（代码/URL用，如：recruitment）', type: 'string'),
        new OA\Property(property: self::getTableName, description: '对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）', type: 'string'),
        new OA\Property(property: self::getDescription, description: '模型描述', type: 'string'),
        new OA\Property(property: self::getIsSystem, description: '是否系统内置：1是（不可删除），0否', type: 'integer'),
        new OA\Property(property: self::getIsCommentable, description: '是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序权重', type: 'integer'),
    ]
)]
class ContentModelCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getAlias = 'alias';

    public const string getTableName = 'tableName';

    public const string getDescription = 'description';

    public const string getIsSystem = 'isSystem';

    public const string getIsCommentable = 'isCommentable';

    public const string getStatus = 'status';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getAlias => 'required',
            self::getTableName => 'required',
            self::getDescription => 'required',
            self::getIsSystem => 'required',
            self::getIsCommentable => 'required',
            self::getStatus => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置模型名称（显示用，如：招聘信息）',
            self::getAlias.'.required' => '请设置模型别名（代码/URL用，如：recruitment）',
            self::getTableName.'.required' => '请设置对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）',
            self::getDescription.'.required' => '请设置模型描述',
            self::getIsSystem.'.required' => '请设置是否系统内置：1是（不可删除），0否',
            self::getIsCommentable.'.required' => '请设置是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
            self::getSort.'.required' => '请设置排序权重',
        ];
    }
}
