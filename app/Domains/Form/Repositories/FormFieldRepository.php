<?php

declare(strict_types=1);

namespace App\Domains\Form\Repositories;

use App\Domains\Form\Entities\FormFieldEntity;
use App\Domains\Form\Models\FormField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class FormFieldRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 FormFieldEntity
     */
    public function saveEntity(FormFieldEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?FormFieldEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return FormFieldEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?FormFieldEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return FormFieldEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('form_fields');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new FormField;
    }
}
