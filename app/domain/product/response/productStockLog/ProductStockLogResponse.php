<?php

declare(strict_types=1);

namespace app\domain\product\response\productStockLog;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductStockLogResponse')]
class ProductStockLogResponse
{
    use ArrayHelper;

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
