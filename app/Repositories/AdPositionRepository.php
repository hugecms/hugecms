<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\AdPositionEntity;
use App\Models\AdPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdPositionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 AdPositionEntity
     */
    public function saveEntity(AdPositionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdPositionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return AdPositionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdPositionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return AdPositionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('ad_positions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdPosition;
    }
}
