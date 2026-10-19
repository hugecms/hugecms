<?php

declare(strict_types=1);

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderCartEntity;
use App\Domains\Order\Models\OrderCart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderCartRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderCartEntity
     */
    public function saveEntity(OrderCartEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderCartEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderCartEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderCartEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderCartEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_carts');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderCart;
    }
}
