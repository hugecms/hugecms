<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\RoleEntity;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class RoleRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 RoleEntity
     */
    public function saveEntity(RoleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?RoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return RoleEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?RoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return RoleEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('roles');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Role;
    }
}
