<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\UserPointLogEntity;
use App\Domains\User\Models\UserPointLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserPointLogRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserPointLogEntity
     */
    public function saveEntity(UserPointLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserPointLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserPointLogEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserPointLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserPointLogEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_point_logs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserPointLog;
    }
}
