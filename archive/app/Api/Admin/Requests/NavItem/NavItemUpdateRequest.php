<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\NavItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NavItemUpdateRequest',
    required: [
        self::getId,
        self::getMenuId,
        self::getParentId,
        self::getTitle,
        self::getLinkType,
        self::getLinkValue,
        self::getOpenType,
        self::getIcon,
        self::getIsActive,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getMenuId, description: '所属菜单集', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID（0代表顶级）', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '菜单显示标题', type: 'string'),
        new OA\Property(property: self::getLinkType, description: '链接类型：custom自定义/content内容/term分类', type: 'string'),
        new OA\Property(property: self::getLinkValue, description: '链接目标值（自定义URL 或 content_id/term_id）', type: 'string'),
        new OA\Property(property: self::getOpenType, description: '打开方式：0本窗口，1新窗口', type: 'integer'),
        new OA\Property(property: self::getIcon, description: '小图标CSS类', type: 'string'),
        new OA\Property(property: self::getIsActive, description: '是否启用：1是，0否', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序权重', type: 'integer'),
    ]
)]
class NavItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getMenuId = 'menuId';

    public const string getParentId = 'parentId';

    public const string getTitle = 'title';

    public const string getLinkType = 'linkType';

    public const string getLinkValue = 'linkValue';

    public const string getOpenType = 'openType';

    public const string getIcon = 'icon';

    public const string getIsActive = 'isActive';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getMenuId => 'required',
            self::getParentId => 'required',
            self::getTitle => 'required',
            self::getLinkType => 'required',
            self::getLinkValue => 'required',
            self::getOpenType => 'required',
            self::getIcon => 'required',
            self::getIsActive => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getMenuId.'.required' => '请设置所属菜单集',
            self::getParentId.'.required' => '请设置父级ID（0代表顶级）',
            self::getTitle.'.required' => '请设置菜单显示标题',
            self::getLinkType.'.required' => '请设置链接类型：custom自定义/content内容/term分类',
            self::getLinkValue.'.required' => '请设置链接目标值（自定义URL 或 content_id/term_id）',
            self::getOpenType.'.required' => '请设置打开方式：0本窗口，1新窗口',
            self::getIcon.'.required' => '请设置小图标CSS类',
            self::getIsActive.'.required' => '请设置是否启用：1是，0否',
            self::getSort.'.required' => '请设置排序权重',
        ];
    }
}
