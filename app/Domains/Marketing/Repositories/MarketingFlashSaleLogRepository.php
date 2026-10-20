<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingFlashSaleLogEntity;
use App\Domains\Marketing\Models\MarketingFlashSaleLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingFlashSaleLogRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingFlashSaleLogEntity
     */
    public function saveEntity(MarketingFlashSaleLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingFlashSaleLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingFlashSaleLogEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingFlashSaleLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingFlashSaleLogEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_flash_sale_logs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingFlashSaleLog;
    }
}
