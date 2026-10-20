<?php

declare(strict_types=1);

namespace App\Domains\Email\Repositories;

use App\Domains\Email\Entities\EmailSubscriptionEntity;
use App\Domains\Email\Models\EmailSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class EmailSubscriptionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 EmailSubscriptionEntity
     */
    public function saveEntity(EmailSubscriptionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?EmailSubscriptionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return EmailSubscriptionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?EmailSubscriptionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return EmailSubscriptionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('email_subscriptions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new EmailSubscription;
    }
}
