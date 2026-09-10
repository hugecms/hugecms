<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ContentPushQueueEntity;
use App\Models\ContentPushQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ContentPushQueueRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ContentPushQueueEntity
     */
    public function saveEntity(ContentPushQueueEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentPushQueueEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ContentPushQueueEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentPushQueueEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ContentPushQueueEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('content_push_queue');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ContentPushQueue;
    }
}
