<?php

declare(strict_types=1);

namespace app\domain\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class AdminUserModel extends Model
{
    use SoftDelete;

    /**
     * 设置表
     */
    protected $name = 'admin_users';

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
        'username',
        'email',
        'phone',
        'password',
        'status',
        'last_login_time',
        'last_login_ip',
        'create_time',
        'update_time',
        'delete_time',
    ];
}
