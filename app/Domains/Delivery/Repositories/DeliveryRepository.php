<?php

declare(strict_types=1);

namespace App\Domains\Delivery\Repositories;

use App\Domains\Delivery\Entities\DeliveryEntity;
use App\Domains\Delivery\Models\Delivery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class DeliveryRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 DeliveryEntity
     */
    public function saveEntity(DeliveryEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?DeliveryEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return DeliveryEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?DeliveryEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return DeliveryEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('delivery');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Delivery;
    }
}
