<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\BlockEntity;
use App\Models\Block;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class BlockRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 BlockEntity
     */
    public function saveEntity(BlockEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?BlockEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return BlockEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?BlockEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return BlockEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('blocks');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Block;
    }
}
