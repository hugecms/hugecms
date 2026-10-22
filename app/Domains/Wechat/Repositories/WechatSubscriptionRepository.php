<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Repositories;

use App\Domains\Wechat\Entities\WechatSubscriptionEntity;
use App\Domains\Wechat\Models\WechatSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class WechatSubscriptionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 WechatSubscriptionEntity
     */
    public function saveEntity(WechatSubscriptionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?WechatSubscriptionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return WechatSubscriptionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?WechatSubscriptionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return WechatSubscriptionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('wechat_subscriptions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new WechatSubscription;
    }
}
