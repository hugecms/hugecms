<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingDistributionCommissionRuleEntity;
use App\Domains\Marketing\Models\MarketingDistributionCommissionRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingDistributionCommissionRuleRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingDistributionCommissionRuleEntity
     */
    public function saveEntity(MarketingDistributionCommissionRuleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingDistributionCommissionRuleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingDistributionCommissionRuleEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingDistributionCommissionRuleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingDistributionCommissionRuleEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_distribution_commission_rules');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingDistributionCommissionRule;
    }
}
