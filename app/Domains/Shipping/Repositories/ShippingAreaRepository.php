<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Repositories;

use App\Domains\Shipping\Entities\ShippingAreaEntity;
use App\Domains\Shipping\Models\ShippingArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShippingAreaRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ShippingAreaEntity
     */
    public function saveEntity(ShippingAreaEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShippingAreaEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ShippingAreaEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShippingAreaEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ShippingAreaEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shipping_areas');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShippingArea;
    }
}
