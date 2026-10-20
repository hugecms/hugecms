<?php

declare(strict_types=1);

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Entities\OrderPromotionEntity;
use App\Domains\Order\Models\OrderPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderPromotionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 OrderPromotionEntity
     */
    public function saveEntity(OrderPromotionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderPromotionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return OrderPromotionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderPromotionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return OrderPromotionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_promotions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderPromotion;
    }
}
