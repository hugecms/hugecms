<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingFlashSaleItemEntity;
use App\Domains\Marketing\Models\MarketingFlashSaleItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingFlashSaleItemRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingFlashSaleItemEntity
     */
    public function saveEntity(MarketingFlashSaleItemEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingFlashSaleItemEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingFlashSaleItemEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingFlashSaleItemEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingFlashSaleItemEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_flash_sale_items');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingFlashSaleItem;
    }
}
