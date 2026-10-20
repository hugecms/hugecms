<?php

declare(strict_types=1);

namespace App\Domains\Site\Repositories;

use App\Domains\Site\Entities\SiteAdPositionEntity;
use App\Domains\Site\Models\SiteAdPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SiteAdPositionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SiteAdPositionEntity
     */
    public function saveEntity(SiteAdPositionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SiteAdPositionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SiteAdPositionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SiteAdPositionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SiteAdPositionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('site_ad_positions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SiteAdPosition;
    }
}
