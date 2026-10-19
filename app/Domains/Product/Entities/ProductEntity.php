<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductEntity')]
class ProductEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCategoryId = 'category_id'; // 分类ID

    public const string getBrandId = 'brand_id'; // 品牌ID

    public const string getName = 'name'; // 商品名称

    public const string getSn = 'sn'; // 商品编码

    public const string getSubtitle = 'subtitle'; // 副标题

    public const string getCover = 'cover'; // 主图

    public const string getImages = 'images'; // 图册(JSON数组)

    public const string getContent = 'content'; // 商品详情

    public const string getPrice = 'price'; // 售价,多规格时为最低规格价

    public const string getMarketPrice = 'market_price'; // 市场价

    public const string getStock = 'stock'; // 库存,单规格商品有效

    public const string getSales = 'sales'; // 销量

    public const string getStatus = 'status'; // 状态:0=下架,1=上架

    public const string getSort = 'sort'; // 排序

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    public const string getDeleteTime = 'delete_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'categoryId', description: '分类ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'brandId', description: '品牌ID', type: 'integer')]
    private int $brandId;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'sn', description: '商品编码', type: 'string')]
    private string $sn;

    #[OA\Property(property: 'subtitle', description: '副标题', type: 'string')]
    private string $subtitle;

    #[OA\Property(property: 'cover', description: '主图', type: 'string')]
    private string $cover;

    #[OA\Property(property: 'images', description: '图册(JSON数组)', type: 'string')]
    private string $images;

    #[OA\Property(property: 'content', description: '商品详情', type: 'string')]
    private string $content;

    #[OA\Property(property: 'price', description: '售价,多规格时为最低规格价', type: 'string')]
    private string $price;

    #[OA\Property(property: 'marketPrice', description: '市场价', type: 'string')]
    private string $marketPrice;

    #[OA\Property(property: 'stock', description: '库存,单规格商品有效', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'sales', description: '销量', type: 'integer')]
    private int $sales;

    #[OA\Property(property: 'status', description: '状态:0=下架,1=上架', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    #[OA\Property(property: 'deleteTime', description: '', type: 'string')]
    private string $deleteTime;

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
     * 获取分类ID
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * 设置分类ID
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 获取品牌ID
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }

    /**
     * 设置品牌ID
     */
    public function setBrandId(int $brandId): void
    {
        $this->brandId = $brandId;
    }

    /**
     * 获取商品名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置商品名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取商品编码
     */
    public function getSn(): string
    {
        return $this->sn;
    }

    /**
     * 设置商品编码
     */
    public function setSn(string $sn): void
    {
        $this->sn = $sn;
    }

    /**
     * 获取副标题
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * 设置副标题
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * 获取主图
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * 设置主图
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * 获取图册(JSON数组)
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置图册(JSON数组)
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取商品详情
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置商品详情
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取售价,多规格时为最低规格价
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置售价,多规格时为最低规格价
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取市场价
     */
    public function getMarketPrice(): string
    {
        return $this->marketPrice;
    }

    /**
     * 设置市场价
     */
    public function setMarketPrice(string $marketPrice): void
    {
        $this->marketPrice = $marketPrice;
    }

    /**
     * 获取库存,单规格商品有效
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * 设置库存,单规格商品有效
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * 获取销量
     */
    public function getSales(): int
    {
        return $this->sales;
    }

    /**
     * 设置销量
     */
    public function setSales(int $sales): void
    {
        $this->sales = $sales;
    }

    /**
     * 获取状态:0=下架,1=上架
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=下架,1=上架
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * 获取
     */
    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    /**
     * 设置
     */
    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
