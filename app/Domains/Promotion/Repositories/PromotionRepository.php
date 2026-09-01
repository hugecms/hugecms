<?php

declare(strict_types=1);

namespace App\Domains\Promotion\Repositories;

use App\Domains\Promotion\Entities\PromotionEntity;
use App\Domains\Promotion\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class PromotionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 PromotionEntity
     */
    public function saveEntity(PromotionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PromotionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return PromotionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PromotionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return PromotionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('promotion');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Promotion;
    }
}
