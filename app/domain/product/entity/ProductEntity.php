<?php

declare(strict_types=1);

namespace app\domain\product\entity;

use Juling\Foundation\Support\ArrayHelper;

class ProductEntity
{
    use ArrayHelper;

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

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    public const string getDeleteTime = 'delete_time'; // 

    private int $id;

    private int $categoryId;

    private int $brandId;

    private string $name;

    private string $sn;

    private string $subtitle;

    private string $cover;

    private string $images;

    private string $content;

    private float $price;

    private float $marketPrice;

    private int $stock;

    private int $sales;

    private int $status;

    private int $sort;

    private string $createTime;

    private string $updateTime;

    private string $deleteTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    public function setBrandId(int $brandId): void
    {
        $this->brandId = $brandId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSn(): string
    {
        return $this->sn;
    }

    public function setSn(string $sn): void
    {
        $this->sn = $sn;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    public function getImages(): string
    {
        return $this->images;
    }

    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getMarketPrice(): float
    {
        return $this->marketPrice;
    }

    public function setMarketPrice(float $marketPrice): void
    {
        $this->marketPrice = $marketPrice;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getSales(): int
    {
        return $this->sales;
    }

    public function setSales(int $sales): void
    {
        $this->sales = $sales;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
