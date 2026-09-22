<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SeoMetaEntity;
use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SeoMetaRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SeoMetaEntity
     */
    public function saveEntity(SeoMetaEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SeoMetaEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SeoMetaEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SeoMetaEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SeoMetaEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('seo_meta');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SeoMeta;
    }
}
