<?php

declare(strict_types=1);

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Entities\AuthRoleEntity;
use App\Domains\Auth\Models\AuthRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AuthRoleRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 AuthRoleEntity
     */
    public function saveEntity(AuthRoleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AuthRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return AuthRoleEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AuthRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return AuthRoleEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('auth_role');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AuthRole;
    }
}
