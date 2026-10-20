<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingDistributionRelationEntity;
use App\Domains\Marketing\Models\MarketingDistributionRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingDistributionRelationRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingDistributionRelationEntity
     */
    public function saveEntity(MarketingDistributionRelationEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingDistributionRelationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingDistributionRelationEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingDistributionRelationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingDistributionRelationEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_distribution_relations');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingDistributionRelation;
    }
}
