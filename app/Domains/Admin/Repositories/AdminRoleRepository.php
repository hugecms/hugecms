<?php

declare(strict_types=1);

namespace App\Domains\Admin\Repositories;

use App\Domains\Admin\Entities\AdminRoleEntity;
use App\Domains\Admin\Models\AdminRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdminRoleRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 AdminRoleEntity
     */
    public function saveEntity(AdminRoleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdminRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return AdminRoleEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdminRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return AdminRoleEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('admin_roles');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdminRole;
    }
}
