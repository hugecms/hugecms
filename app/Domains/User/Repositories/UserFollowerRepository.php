<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\UserFollowerEntity;
use App\Domains\User\Models\UserFollower;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserFollowerRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserFollowerEntity
     */
    public function saveEntity(UserFollowerEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserFollowerEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserFollowerEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserFollowerEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserFollowerEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_followers');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserFollower;
    }
}
