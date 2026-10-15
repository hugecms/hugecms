<?php

declare(strict_types=1);

namespace app\domain\user\model;

use think\Model;
use think\model\concern\SoftDelete;

class UserAddressModel extends Model
{
    use SoftDelete;

    /**
     * 设置表
     */
    protected $name = 'user_addresses';

    /**
     * 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型.
     */
    protected string $autoWriteTimestamp = 'datetime';

    /**
     * 创建时间字段 false表示关闭.
     */
    protected string $createTime = 'created_time';

    /**
     * 更新时间字段 false表示关闭.
     */
    protected string $updateTime = 'updated_time';

    /**
     * 软删除字段
     */
    protected string $deleteTime = 'deleted_time';

    /**
     * 设置字段
     */
    protected array $field = [
        'id',
        'user_id',
        'receiver_name',
        'receiver_phone',
        'receiver_region',
        'receiver_address',
        'is_default',
        'create_time',
        'update_time',
        'delete_time',
    ];
}
