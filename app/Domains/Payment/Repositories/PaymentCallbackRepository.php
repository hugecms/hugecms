<?php

declare(strict_types=1);

namespace App\Domains\Payment\Repositories;

use App\Domains\Payment\Entities\PaymentCallbackEntity;
use App\Domains\Payment\Models\PaymentCallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class PaymentCallbackRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 PaymentCallbackEntity
     */
    public function saveEntity(PaymentCallbackEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PaymentCallbackEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return PaymentCallbackEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PaymentCallbackEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return PaymentCallbackEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('payment_callbacks');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new PaymentCallback;
    }
}
