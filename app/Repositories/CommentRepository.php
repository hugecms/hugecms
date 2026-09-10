<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\CommentEntity;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class CommentRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 CommentEntity
     */
    public function saveEntity(CommentEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CommentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return CommentEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CommentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return CommentEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('comments');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Comment;
    }
}
