<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Repositories;

use App\Domains\Marketing\Entities\MarketingGroupBuyTeamMemberEntity;
use App\Domains\Marketing\Models\MarketingGroupBuyTeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MarketingGroupBuyTeamMemberRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MarketingGroupBuyTeamMemberEntity
     */
    public function saveEntity(MarketingGroupBuyTeamMemberEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MarketingGroupBuyTeamMemberEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MarketingGroupBuyTeamMemberEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MarketingGroupBuyTeamMemberEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MarketingGroupBuyTeamMemberEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('marketing_group_buy_team_members');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MarketingGroupBuyTeamMember;
    }
}
