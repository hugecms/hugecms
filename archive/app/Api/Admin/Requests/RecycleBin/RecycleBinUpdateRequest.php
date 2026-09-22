<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\RecycleBin;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RecycleBinUpdateRequest',
    required: [
        self::getId,
        self::getDeletedBy,
        self::getTargetType,
        self::getTargetId,
        self::getOriginalData,
        self::getRestoreData,
        self::getRetentionDays,
        self::getExpireAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getDeletedBy, description: '删除人用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '原对象类型：content/term/attachment/user/form_submission/comment', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '原对象ID', type: 'string'),
        new OA\Property(property: self::getOriginalData, description: '删除前的全量数据快照（JSON）', type: 'string'),
        new OA\Property(property: self::getRestoreData, description: '恢复时所需的数据映射（如恢复时需新建ID）', type: 'string'),
        new OA\Property(property: self::getRetentionDays, description: '保留天数（超时由 Scheduler 物理清除）', type: 'integer'),
        new OA\Property(property: self::getExpireAt, description: '过期时间（虚拟生成列）', type: 'string'),
    ]
)]
class RecycleBinUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getDeletedBy = 'deletedBy';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getOriginalData = 'originalData';

    public const string getRestoreData = 'restoreData';

    public const string getRetentionDays = 'retentionDays';

    public const string getExpireAt = 'expireAt';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getDeletedBy => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getOriginalData => 'required',
            self::getRestoreData => 'required',
            self::getRetentionDays => 'required',
            self::getExpireAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getDeletedBy.'.required' => '请设置删除人用户ID',
            self::getTargetType.'.required' => '请设置原对象类型：content/term/attachment/user/form_submission/comment',
            self::getTargetId.'.required' => '请设置原对象ID',
            self::getOriginalData.'.required' => '请设置删除前的全量数据快照（JSON）',
            self::getRestoreData.'.required' => '请设置恢复时所需的数据映射（如恢复时需新建ID）',
            self::getRetentionDays.'.required' => '请设置保留天数（超时由 Scheduler 物理清除）',
            self::getExpireAt.'.required' => '请设置过期时间（虚拟生成列）',
        ];
    }
}
