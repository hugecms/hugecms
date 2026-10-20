<?php

declare(strict_types=1);

namespace App\Domains\Content\Repositories;

use App\Domains\Content\Entities\ContentCommentLikeEntity;
use App\Domains\Content\Models\ContentCommentLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ContentCommentLikeRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ContentCommentLikeEntity
     */
    public function saveEntity(ContentCommentLikeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentCommentLikeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ContentCommentLikeEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentCommentLikeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ContentCommentLikeEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('content_comment_likes');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ContentCommentLike;
    }
}
