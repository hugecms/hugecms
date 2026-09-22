<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\AttachmentRelationEntity;
use App\Models\AttachmentRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AttachmentRelationRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 AttachmentRelationEntity
     */
    public function saveEntity(AttachmentRelationEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AttachmentRelationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return AttachmentRelationEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AttachmentRelationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return AttachmentRelationEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('attachment_relations');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AttachmentRelation;
    }
}
