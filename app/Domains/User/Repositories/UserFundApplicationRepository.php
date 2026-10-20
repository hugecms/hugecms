<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\UserFundApplicationEntity;
use App\Domains\User\Models\UserFundApplication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserFundApplicationRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserFundApplicationEntity
     */
    public function saveEntity(UserFundApplicationEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserFundApplicationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserFundApplicationEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserFundApplicationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserFundApplicationEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_fund_applications');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserFundApplication;
    }
}
