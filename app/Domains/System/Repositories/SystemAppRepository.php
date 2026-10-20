<?php

declare(strict_types=1);

namespace App\Domains\System\Repositories;

use App\Domains\System\Entities\SystemAppEntity;
use App\Domains\System\Models\SystemApp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemAppRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SystemAppEntity
     */
    public function saveEntity(SystemAppEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemAppEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SystemAppEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemAppEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SystemAppEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_apps');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemApp;
    }
}
