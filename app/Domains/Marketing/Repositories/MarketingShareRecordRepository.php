<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingShareRecordEntity;
use App\Domains\Marketing\Models\MarketingShareRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingShareRecordRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingShareRecordEntity
     */
    public function saveEntity(MarketingShareRecordEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingShareRecordEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingShareRecordEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingShareRecordEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingShareRecordEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_share_records');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingShareRecord;
    }
}
