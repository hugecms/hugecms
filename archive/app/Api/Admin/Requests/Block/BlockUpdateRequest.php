<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BlockUpdateRequest',
    required: [
        self::getId,
        self::getBlockName,
        self::getBlockType,
        self::getContent,
        self::getCss,
        self::getJs,
        self::getIsGlobal,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getBlockName, description: '区块名称', type: 'string'),
        new OA\Property(property: self::getBlockType, description: '区块类型：header/footer/banner/content/sidebar/custom', type: 'string'),
        new OA\Property(property: self::getContent, description: '区块内容（HTML/JSON）', type: 'string'),
        new OA\Property(property: self::getCss, description: '自定义CSS样式', type: 'string'),
        new OA\Property(property: self::getJs, description: '自定义JS脚本', type: 'string'),
        new OA\Property(property: self::getIsGlobal, description: '是否全局区块（全站复用）：1是，0否', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class BlockUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getBlockName = 'blockName';

    public const string getBlockType = 'blockType';

    public const string getContent = 'content';

    public const string getCss = 'css';

    public const string getJs = 'js';

    public const string getIsGlobal = 'isGlobal';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getBlockName => 'required',
            self::getBlockType => 'required',
            self::getContent => 'required',
            self::getCss => 'required',
            self::getJs => 'required',
            self::getIsGlobal => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getBlockName.'.required' => '请设置区块名称',
            self::getBlockType.'.required' => '请设置区块类型：header/footer/banner/content/sidebar/custom',
            self::getContent.'.required' => '请设置区块内容（HTML/JSON）',
            self::getCss.'.required' => '请设置自定义CSS样式',
            self::getJs.'.required' => '请设置自定义JS脚本',
            self::getIsGlobal.'.required' => '请设置是否全局区块（全站复用）：1是，0否',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
