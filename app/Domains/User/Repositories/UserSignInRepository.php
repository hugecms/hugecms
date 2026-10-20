<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\UserSignInEntity;
use App\Domains\User\Models\UserSignIn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserSignInRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserSignInEntity
     */
    public function saveEntity(UserSignInEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserSignInEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserSignInEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserSignInEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserSignInEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_sign_ins');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserSignIn;
    }
}
