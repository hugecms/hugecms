<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ShortLinkEntity;
use App\Models\ShortLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShortLinkRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ShortLinkEntity
     */
    public function saveEntity(ShortLinkEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShortLinkEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ShortLinkEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShortLinkEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ShortLinkEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('short_links');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShortLink;
    }
}
