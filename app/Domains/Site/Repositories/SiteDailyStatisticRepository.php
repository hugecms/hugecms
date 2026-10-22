<?php

declare(strict_types=1);

namespace App\Domains\Site\Repositories;

use App\Domains\Site\Entities\SiteDailyStatisticEntity;
use App\Domains\Site\Models\SiteDailyStatistic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SiteDailyStatisticRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SiteDailyStatisticEntity
     */
    public function saveEntity(SiteDailyStatisticEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SiteDailyStatisticEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SiteDailyStatisticEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SiteDailyStatisticEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SiteDailyStatisticEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('site_daily_statistics');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SiteDailyStatistic;
    }
}
