<?php

declare(strict_types=1);

namespace App\Domains\Site\Repositories;

use App\Domains\Site\Entities\SiteNavEntity;
use App\Domains\Site\Models\SiteNav;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SiteNavRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SiteNavEntity
     */
    public function saveEntity(SiteNavEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SiteNavEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SiteNavEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SiteNavEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SiteNavEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('site_navs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SiteNav;
    }
}
