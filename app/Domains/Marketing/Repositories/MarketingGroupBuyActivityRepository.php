<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingGroupBuyActivityEntity;
use App\Domains\Marketing\Models\MarketingGroupBuyActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingGroupBuyActivityRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingGroupBuyActivityEntity
     */
    public function saveEntity(MarketingGroupBuyActivityEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingGroupBuyActivityEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingGroupBuyActivityEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingGroupBuyActivityEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingGroupBuyActivityEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_group_buy_activities');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingGroupBuyActivity;
    }
}
