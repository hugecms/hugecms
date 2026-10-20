<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Repositories;

use App\Domains\Wechat\Entities\WechatQrcodeEntity;
use App\Domains\Wechat\Models\WechatQrcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class WechatQrcodeRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 WechatQrcodeEntity
     */
    public function saveEntity(WechatQrcodeEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?WechatQrcodeEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return WechatQrcodeEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?WechatQrcodeEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return WechatQrcodeEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('wechat_qrcodes');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new WechatQrcode;
    }
}
