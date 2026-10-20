<?php

declare(strict_types=1);

namespace App\Domains\Chat\Repositories;

use App\Domains\Chat\Entities\ChatServiceEntity;
use App\Domains\Chat\Models\ChatService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ChatServiceRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ChatServiceEntity
     */
    public function saveEntity(ChatServiceEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ChatServiceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ChatServiceEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ChatServiceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ChatServiceEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('chat_services');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ChatService;
    }
}
