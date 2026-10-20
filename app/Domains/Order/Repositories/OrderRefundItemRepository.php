<?php

declare(strict_types=1);

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderRefundItemEntity;
use App\Domains\Order\Models\OrderRefundItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderRefundItemRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderRefundItemEntity
     */
    public function saveEntity(OrderRefundItemEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderRefundItemEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderRefundItemEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderRefundItemEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderRefundItemEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_refund_items');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderRefundItem;
    }
}
