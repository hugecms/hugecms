<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\FormTemplateEntity;
use App\Models\FormTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class FormTemplateRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 FormTemplateEntity
     */
    public function saveEntity(FormTemplateEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?FormTemplateEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return FormTemplateEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?FormTemplateEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return FormTemplateEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('form_templates');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new FormTemplate;
    }
}
