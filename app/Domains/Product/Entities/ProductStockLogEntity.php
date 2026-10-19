<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductStockLogEntity')]
class ProductStockLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID,0=单规格商品

    public const string getChangeType = 'change_type'; // 变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整

    public const string getChangeQuantity = 'change_quantity'; // 变动数量,正数入库负数出库

    public const string getBeforeStock = 'before_stock'; // 变动前库存

    public const string getAfterStock = 'after_stock'; // 变动后库存

    public const string getRelatedId = 'related_id'; // 关联单据ID(订单/退款单)

    public const string getUserType = 'user_type'; // 操作账户类型:admin,user,system

    public const string getUserId = 'user_id'; // 操作人ID

    public const string getRemark = 'remark'; // 备注

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,0=单规格商品', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'changeType', description: '变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整', type: 'string')]
    private string $changeType;

    #[OA\Property(property: 'changeQuantity', description: '变动数量,正数入库负数出库', type: 'integer')]
    private int $changeQuantity;

    #[OA\Property(property: 'beforeStock', description: '变动前库存', type: 'integer')]
    private int $beforeStock;

    #[OA\Property(property: 'afterStock', description: '变动后库存', type: 'integer')]
    private int $afterStock;

    #[OA\Property(property: 'relatedId', description: '关联单据ID(订单/退款单)', type: 'integer')]
    private int $relatedId;

    #[OA\Property(property: 'userType', description: '操作账户类型:admin,user,system', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '操作人ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'remark', description: '备注', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取商品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取SKU ID,0=单规格商品
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID,0=单规格商品
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整
     */
    public function getChangeType(): string
    {
        return $this->changeType;
    }

    /**
     * 设置变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整
     */
    public function setChangeType(string $changeType): void
    {
        $this->changeType = $changeType;
    }

    /**
     * 获取变动数量,正数入库负数出库
     */
    public function getChangeQuantity(): int
    {
        return $this->changeQuantity;
    }

    /**
     * 设置变动数量,正数入库负数出库
     */
    public function setChangeQuantity(int $changeQuantity): void
    {
        $this->changeQuantity = $changeQuantity;
    }

    /**
     * 获取变动前库存
     */
    public function getBeforeStock(): int
    {
        return $this->beforeStock;
    }

    /**
     * 设置变动前库存
     */
    public function setBeforeStock(int $beforeStock): void
    {
        $this->beforeStock = $beforeStock;
    }

    /**
     * 获取变动后库存
     */
    public function getAfterStock(): int
    {
        return $this->afterStock;
    }

    /**
     * 设置变动后库存
     */
    public function setAfterStock(int $afterStock): void
    {
        $this->afterStock = $afterStock;
    }

    /**
     * 获取关联单据ID(订单/退款单)
     */
    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    /**
     * 设置关联单据ID(订单/退款单)
     */
    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    /**
     * 获取操作账户类型:admin,user,system
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置操作账户类型:admin,user,system
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * 获取操作人ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置操作人ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
