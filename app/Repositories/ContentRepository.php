<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ContentEntity;
use App\Models\Content;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ContentRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ContentEntity
     */
    public function saveEntity(ContentEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ContentEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ContentEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('contents');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Content;
    }
}
