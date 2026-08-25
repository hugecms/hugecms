<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\MenuEntity;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MenuRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MenuEntity
     */
    public function saveEntity(MenuEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MenuEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MenuEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MenuEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MenuEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('menus');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Menu;
    }
}
