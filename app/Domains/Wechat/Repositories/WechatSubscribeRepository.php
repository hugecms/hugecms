<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Repositories;

use App\Domains\Wechat\Entities\WechatSubscribeEntity;
use App\Domains\Wechat\Models\WechatSubscribe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class WechatSubscribeRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 WechatSubscribeEntity
     */
    public function saveEntity(WechatSubscribeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?WechatSubscribeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return WechatSubscribeEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?WechatSubscribeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return WechatSubscribeEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('wechat_subscribes');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new WechatSubscribe;
    }
}
