<?php

declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Domains\Product\Entities\ProductVolumePriceEntity;
use App\Domains\Product\Models\ProductVolumePrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ProductVolumePriceRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ProductVolumePriceEntity
     */
    public function saveEntity(ProductVolumePriceEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductVolumePriceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ProductVolumePriceEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductVolumePriceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ProductVolumePriceEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('product_volume_prices');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ProductVolumePrice;
    }
}
