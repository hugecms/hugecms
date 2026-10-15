<?php

declare(strict_types=1);

namespace app\domain\product\entity;

use Juling\Foundation\Support\ArrayHelper;

class ProductStockLogEntity
{
    use ArrayHelper;

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

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private int $productId;

    private int $skuId;

    private string $changeType;

    private int $changeQuantity;

    private int $beforeStock;

    private int $afterStock;

    private int $relatedId;

    private string $userType;

    private int $userId;

    private string $remark;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getSkuId(): int
    {
        return $this->skuId;
    }

    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    public function getChangeType(): string
    {
        return $this->changeType;
    }

    public function setChangeType(string $changeType): void
    {
        $this->changeType = $changeType;
    }

    public function getChangeQuantity(): int
    {
        return $this->changeQuantity;
    }

    public function setChangeQuantity(int $changeQuantity): void
    {
        $this->changeQuantity = $changeQuantity;
    }

    public function getBeforeStock(): int
    {
        return $this->beforeStock;
    }

    public function setBeforeStock(int $beforeStock): void
    {
        $this->beforeStock = $beforeStock;
    }

    public function getAfterStock(): int
    {
        return $this->afterStock;
    }

    public function setAfterStock(int $afterStock): void
    {
        $this->afterStock = $afterStock;
    }

    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getRemark(): string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
