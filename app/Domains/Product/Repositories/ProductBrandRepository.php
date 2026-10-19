<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductBrandEntity;
use App\Domains\Product\Models\ProductBrand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductBrandRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductBrandEntity
     */
    public function saveEntity(ProductBrandEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductBrandEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductBrandEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductBrandEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductBrandEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_brands');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductBrand;
    }
}
