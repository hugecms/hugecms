<?php

declare(strict_types=1);

namespace App\Domains\Site\Repositories;

use App\Domains\Site\Entities\SiteSearchKeywordEntity;
use App\Domains\Site\Models\SiteSearchKeyword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SiteSearchKeywordRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SiteSearchKeywordEntity
     */
    public function saveEntity(SiteSearchKeywordEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SiteSearchKeywordEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SiteSearchKeywordEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SiteSearchKeywordEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SiteSearchKeywordEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('site_search_keywords');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SiteSearchKeyword;
    }
}
