<?php

declare(strict_types=1);

namespace App\Domains\Payment\Repositories;

use App\Domains\Payment\Entities\PaymentChannelEntity;
use App\Domains\Payment\Models\PaymentChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class PaymentChannelRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 PaymentChannelEntity
     */
    public function saveEntity(PaymentChannelEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?PaymentChannelEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return PaymentChannelEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?PaymentChannelEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return PaymentChannelEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('payment_channels');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new PaymentChannel;
    }
}
