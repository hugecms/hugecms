<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Repositories;

use App\Domains\Wechat\Entities\WechatMessageEntity;
use App\Domains\Wechat\Models\WechatMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class WechatMessageRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 WechatMessageEntity
     */
    public function saveEntity(WechatMessageEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?WechatMessageEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return WechatMessageEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?WechatMessageEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return WechatMessageEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('wechat_messages');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new WechatMessage;
    }
}
