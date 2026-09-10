<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\TermRelationshipEntity;
use App\Models\TermRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class TermRelationshipRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 TermRelationshipEntity
     */
    public function saveEntity(TermRelationshipEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?TermRelationshipEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return TermRelationshipEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?TermRelationshipEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return TermRelationshipEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('term_relationships');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new TermRelationship;
    }
}
