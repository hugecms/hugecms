<?php

declare(strict_types=1);

namespace app\domain\order\model;

use think\Model;

class OrderCartModel extends Model
{
    /**
     * 设置表
     */
    protected $name = 'order_carts';

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
     * 设置字段
     */
    protected array $field = [
        'id',
        'user_id',
        'product_id',
        'sku_id',
        'product_name',
        'image',
        'price',
        'quantity',
        'checked',
        'create_time',
        'update_time',
    ];
}
