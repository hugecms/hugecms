<?php

declare(strict_types=1);

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderShipmentItemEntity;
use App\Domains\Order\Models\OrderShipmentItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderShipmentItemRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderShipmentItemEntity
     */
    public function saveEntity(OrderShipmentItemEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderShipmentItemEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderShipmentItemEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderShipmentItemEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderShipmentItemEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_shipment_items');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderShipmentItem;
    }
}
