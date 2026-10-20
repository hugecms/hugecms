<?php

declare(strict_types=1);

namespace App\Domains\Chat\Repositories;

use App\Domains\Chat\Entities\ChatBotRuleEntity;
use App\Domains\Chat\Models\ChatBotRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ChatBotRuleRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ChatBotRuleEntity
     */
    public function saveEntity(ChatBotRuleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ChatBotRuleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ChatBotRuleEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ChatBotRuleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ChatBotRuleEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('chat_bot_rules');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ChatBotRule;
    }
}
