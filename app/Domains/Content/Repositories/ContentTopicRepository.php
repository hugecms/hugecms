<?php

declare(strict_types=1);

namespace App\Domains\Content\Repositories;

use App\Domains\Content\Entities\ContentTopicEntity;
use App\Domains\Content\Models\ContentTopic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ContentTopicRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ContentTopicEntity
     */
    public function saveEntity(ContentTopicEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentTopicEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ContentTopicEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentTopicEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ContentTopicEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('content_topics');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ContentTopic;
    }
}
