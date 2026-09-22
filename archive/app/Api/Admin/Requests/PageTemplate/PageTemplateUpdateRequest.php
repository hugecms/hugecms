<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\PageTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PageTemplateUpdateRequest',
    required: [
        self::getId,
        self::getTemplateName,
        self::getTemplateCode,
        self::getCategory,
        self::getPreviewImage,
        self::getContent,
        self::getIsDefault,
        self::getIsSystem,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTemplateName, description: '模板名称', type: 'string'),
        new OA\Property(property: self::getTemplateCode, description: '模板代码（唯一标识）', type: 'string'),
        new OA\Property(property: self::getCategory, description: '类别：page页面/post文章/term分类模板', type: 'string'),
        new OA\Property(property: self::getPreviewImage, description: '预览图URL', type: 'string'),
        new OA\Property(property: self::getContent, description: '模板内容（HTML/JSON结构）', type: 'string'),
        new OA\Property(property: self::getIsDefault, description: '是否默认模板', type: 'integer'),
        new OA\Property(property: self::getIsSystem, description: '是否系统内置', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class PageTemplateUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTemplateName = 'templateName';

    public const string getTemplateCode = 'templateCode';

    public const string getCategory = 'category';

    public const string getPreviewImage = 'previewImage';

    public const string getContent = 'content';

    public const string getIsDefault = 'isDefault';

    public const string getIsSystem = 'isSystem';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getTemplateName => 'required',
            self::getTemplateCode => 'required',
            self::getCategory => 'required',
            self::getPreviewImage => 'required',
            self::getContent => 'required',
            self::getIsDefault => 'required',
            self::getIsSystem => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getTemplateName.'.required' => '请设置模板名称',
            self::getTemplateCode.'.required' => '请设置模板代码（唯一标识）',
            self::getCategory.'.required' => '请设置类别：page页面/post文章/term分类模板',
            self::getPreviewImage.'.required' => '请设置预览图URL',
            self::getContent.'.required' => '请设置模板内容（HTML/JSON结构）',
            self::getIsDefault.'.required' => '请设置是否默认模板',
            self::getIsSystem.'.required' => '请设置是否系统内置',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
