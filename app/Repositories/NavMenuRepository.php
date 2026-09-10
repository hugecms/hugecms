<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\NavMenuEntity;
use App\Models\NavMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class NavMenuRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 NavMenuEntity
     */
    public function saveEntity(NavMenuEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?NavMenuEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return NavMenuEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?NavMenuEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return NavMenuEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('nav_menus');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new NavMenu;
    }
}
