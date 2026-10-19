<?php

declare(strict_types=1);

namespace App\Domains\Content\Repositories;

use App\Domains\Content\Entities\ContentPageEntity;
use App\Domains\Content\Models\ContentPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ContentPageRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ContentPageEntity
     */
    public function saveEntity(ContentPageEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentPageEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ContentPageEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentPageEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ContentPageEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('content_pages');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ContentPage;
    }
}
