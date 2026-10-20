<?php

declare(strict_types=1);

namespace App\Domains\System\Repositories;

use App\Domains\System\Entities\SystemSmsLogEntity;
use App\Domains\System\Models\SystemSmsLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemSmsLogRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SystemSmsLogEntity
     */
    public function saveEntity(SystemSmsLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemSmsLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SystemSmsLogEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemSmsLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SystemSmsLogEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_sms_logs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemSmsLog;
    }
}
