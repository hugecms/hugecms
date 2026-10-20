<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductAttributeEntity;
use App\Domains\Product\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductAttributeEntity
     */
    public function saveEntity(ProductAttributeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductAttributeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductAttributeEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductAttributeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductAttributeEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_attributes');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductAttribute;
    }
}
