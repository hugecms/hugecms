<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderRefundLog;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderRefundLogResponse')]
class OrderRefundLogResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refundId', description: '退款单ID', type: 'integer')]
    private int $refundId;

    #[OA\Property(property: 'operatorType', description: '操作账户类型:admin,user,system', type: 'string')]
    private string $operatorType;

    #[OA\Property(property: 'operatorId', description: '操作人ID', type: 'integer')]
    private int $operatorId;

    #[OA\Property(property: 'operator', description: '操作人名称快照', type: 'string')]
    private string $operator;

    #[OA\Property(property: 'content', description: '协商内容/操作说明', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '凭证图片(JSON数组)', type: 'string')]
    private string $images;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取退款单ID
     */
    public function getRefundId(): int
    {
        return $this->refundId;
    }

    /**
     * 设置退款单ID
     */
    public function setRefundId(int $refundId): void
    {
        $this->refundId = $refundId;
    }

    /**
     * 获取操作账户类型:admin,user,system
     */
    public function getOperatorType(): string
    {
        return $this->operatorType;
    }

    /**
     * 设置操作账户类型:admin,user,system
     */
    public function setOperatorType(string $operatorType): void
    {
        $this->operatorType = $operatorType;
    }

    /**
     * 获取操作人ID
     */
    public function getOperatorId(): int
    {
        return $this->operatorId;
    }

    /**
     * 设置操作人ID
     */
    public function setOperatorId(int $operatorId): void
    {
        $this->operatorId = $operatorId;
    }

    /**
     * 获取操作人名称快照
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * 设置操作人名称快照
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * 获取协商内容/操作说明
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置协商内容/操作说明
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取凭证图片(JSON数组)
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置凭证图片(JSON数组)
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
