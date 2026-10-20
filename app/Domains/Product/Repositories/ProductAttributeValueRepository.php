<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductAttributeValueEntity;
use App\Domains\Product\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductAttributeValueRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductAttributeValueEntity
     */
    public function saveEntity(ProductAttributeValueEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductAttributeValueEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductAttributeValueEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductAttributeValueEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductAttributeValueEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_attribute_values');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductAttributeValue;
    }
}
