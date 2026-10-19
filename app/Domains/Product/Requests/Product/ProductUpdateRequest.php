<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductUpdateRequest',
    required: [
        self::getId,
        self::getCategoryId,
        self::getBrandId,
        self::getName,
        self::getSn,
        self::getSubtitle,
        self::getCover,
        self::getImages,
        self::getContent,
        self::getPrice,
        self::getMarketPrice,
        self::getStock,
        self::getSales,
        self::getStatus,
        self::getSort,
        self::getCreateTime,
        self::getUpdateTime,
        self::getDeleteTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getBrandId, description: '品牌ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getSn, description: '商品编码', type: 'string'),
        new OA\Property(property: self::getSubtitle, description: '副标题', type: 'string'),
        new OA\Property(property: self::getCover, description: '主图', type: 'string'),
        new OA\Property(property: self::getImages, description: '图册(JSON数组)', type: 'string'),
        new OA\Property(property: self::getContent, description: '商品详情', type: 'string'),
        new OA\Property(property: self::getPrice, description: '售价,多规格时为最低规格价', type: 'string'),
        new OA\Property(property: self::getMarketPrice, description: '市场价', type: 'string'),
        new OA\Property(property: self::getStock, description: '库存,单规格商品有效', type: 'integer'),
        new OA\Property(property: self::getSales, description: '销量', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=下架,1=上架', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
        new OA\Property(property: self::getDeleteTime, description: '', type: 'string'),
    ]
)]
class ProductUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategoryId = 'categoryId';

    public const string getBrandId = 'brandId';

    public const string getName = 'name';

    public const string getSn = 'sn';

    public const string getSubtitle = 'subtitle';

    public const string getCover = 'cover';

    public const string getImages = 'images';

    public const string getContent = 'content';

    public const string getPrice = 'price';

    public const string getMarketPrice = 'marketPrice';

    public const string getStock = 'stock';

    public const string getSales = 'sales';

    public const string getStatus = 'status';

    public const string getSort = 'sort';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public const string getDeleteTime = 'deleteTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCategoryId => 'required',
            self::getBrandId => 'required',
            self::getName => 'required',
            self::getSn => 'required',
            self::getSubtitle => 'required',
            self::getCover => 'required',
            self::getImages => 'required',
            self::getContent => 'required',
            self::getPrice => 'required',
            self::getMarketPrice => 'required',
            self::getStock => 'required',
            self::getSales => 'required',
            self::getStatus => 'required',
            self::getSort => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
            self::getDeleteTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCategoryId.'.required' => '请设置分类ID',
            self::getBrandId.'.required' => '请设置品牌ID',
            self::getName.'.required' => '请设置商品名称',
            self::getSn.'.required' => '请设置商品编码',
            self::getSubtitle.'.required' => '请设置副标题',
            self::getCover.'.required' => '请设置主图',
            self::getImages.'.required' => '请设置图册(JSON数组)',
            self::getContent.'.required' => '请设置商品详情',
            self::getPrice.'.required' => '请设置售价,多规格时为最低规格价',
            self::getMarketPrice.'.required' => '请设置市场价',
            self::getStock.'.required' => '请设置库存,单规格商品有效',
            self::getSales.'.required' => '请设置销量',
            self::getStatus.'.required' => '请设置状态:0=下架,1=上架',
            self::getSort.'.required' => '请设置排序',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
            self::getDeleteTime.'.required' => '请设置',
        ];
    }
}
