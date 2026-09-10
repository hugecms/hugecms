<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Redirect;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RedirectUpdateRequest',
    required: [
        self::getId,
        self::getSourcePath,
        self::getTargetPath,
        self::getStatusCode,
        self::getHits,
        self::getLastHitAt,
        self::getRemark,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSourcePath, description: '来源路径（站内相对路径，以 / 开头）', type: 'string'),
        new OA\Property(property: self::getTargetPath, description: '目标路径（相对路径或完整URL）', type: 'string'),
        new OA\Property(property: self::getStatusCode, description: 'HTTP状态码：301永久重定向，302临时重定向', type: 'integer'),
        new OA\Property(property: self::getHits, description: '命中次数（冗余计数，事务内维护）', type: 'integer'),
        new OA\Property(property: self::getLastHitAt, description: '最后命中时间', type: 'string'),
        new OA\Property(property: self::getRemark, description: '备注（如：slug 改版、栏目迁移）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class RedirectUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSourcePath = 'sourcePath';

    public const string getTargetPath = 'targetPath';

    public const string getStatusCode = 'statusCode';

    public const string getHits = 'hits';

    public const string getLastHitAt = 'lastHitAt';

    public const string getRemark = 'remark';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getSourcePath => 'required',
            self::getTargetPath => 'required',
            self::getStatusCode => 'required',
            self::getHits => 'required',
            self::getLastHitAt => 'required',
            self::getRemark => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getSourcePath.'.required' => '请设置来源路径（站内相对路径，以 / 开头）',
            self::getTargetPath.'.required' => '请设置目标路径（相对路径或完整URL）',
            self::getStatusCode.'.required' => '请设置HTTP状态码：301永久重定向，302临时重定向',
            self::getHits.'.required' => '请设置命中次数（冗余计数，事务内维护）',
            self::getLastHitAt.'.required' => '请设置最后命中时间',
            self::getRemark.'.required' => '请设置备注（如：slug 改版、栏目迁移）',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
