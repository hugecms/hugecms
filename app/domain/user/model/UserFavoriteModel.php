<?php

declare(strict_types=1);

namespace app\domain\user\model;

use think\Model;

class UserFavoriteModel extends Model
{
    /**
     * 设置表
     */
    protected $name = 'user_favorites';

    /**
     * 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型.
     */
    protected string $autoWriteTimestamp = 'datetime';

    /**
     * 创建时间字段 false表示关闭.
     */
    protected string $createTime = 'created_time';

    /**
     * 设置字段
     */
    protected array $field = [
        'id',
        'user_id',
        'target_type',
        'target_id',
        'create_time',
    ];
}
