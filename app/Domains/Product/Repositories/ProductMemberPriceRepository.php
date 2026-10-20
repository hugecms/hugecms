<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductMemberPriceEntity;
use App\Domains\Product\Models\ProductMemberPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductMemberPriceRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductMemberPriceEntity
     */
    public function saveEntity(ProductMemberPriceEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductMemberPriceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductMemberPriceEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductMemberPriceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductMemberPriceEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_member_prices');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductMemberPrice;
    }
}
