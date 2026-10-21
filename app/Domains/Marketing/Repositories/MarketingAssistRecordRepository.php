<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingAssistRecordEntity;
use App\Domains\Marketing\Models\MarketingAssistRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingAssistRecordRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingAssistRecordEntity
     */
    public function saveEntity(MarketingAssistRecordEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingAssistRecordEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingAssistRecordEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingAssistRecordEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingAssistRecordEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_assist_records');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingAssistRecord;
    }
}
