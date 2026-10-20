<?php

declare(strict_types=1);

namespace App\Domains\Chat\Repositories;

use App\Domains\Chat\Entities\ChatFaqEntity;
use App\Domains\Chat\Models\ChatFaq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ChatFaqRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ChatFaqEntity
     */
    public function saveEntity(ChatFaqEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ChatFaqEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ChatFaqEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ChatFaqEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ChatFaqEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('chat_faqs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ChatFaq;
    }
}
