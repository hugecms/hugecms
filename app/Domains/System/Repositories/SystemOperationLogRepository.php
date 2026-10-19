<?php

declare(strict_types=1);

namespace App\Domains\System\Repositories;

use App\Domains\System\Entities\SystemOperationLogEntity;
use App\Domains\System\Models\SystemOperationLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemOperationLogRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SystemOperationLogEntity
     */
    public function saveEntity(SystemOperationLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemOperationLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SystemOperationLogEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemOperationLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SystemOperationLogEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_operation_logs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemOperationLog;
    }
}
