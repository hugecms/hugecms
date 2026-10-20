<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductTypeEntity;
use App\Domains\Product\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductTypeRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductTypeEntity
     */
    public function saveEntity(ProductTypeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductTypeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductTypeEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductTypeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductTypeEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_types');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductType;
    }
}
