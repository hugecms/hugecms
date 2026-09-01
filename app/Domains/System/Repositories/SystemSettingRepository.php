<?php

declare(strict_types=1);

namespace App\Domains\System\Repositories;

use App\Domains\System\Entities\SystemSettingEntity;
use App\Domains\System\Models\SystemSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemSettingRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SystemSettingEntity
     */
    public function saveEntity(SystemSettingEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemSettingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SystemSettingEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemSettingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SystemSettingEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_setting');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemSetting;
    }
}
