<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\FriendLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FriendLinkCreateRequest',
    required: [
        self::getCategory,
        self::getSiteName,
        self::getSiteUrl,
        self::getLogoUrl,
        self::getDescription,
        self::getContactEmail,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getCategory, description: '链接分类（如：合作伙伴、友情链接）', type: 'string'),
        new OA\Property(property: self::getSiteName, description: '网站名称', type: 'string'),
        new OA\Property(property: self::getSiteUrl, description: '网站URL', type: 'string'),
        new OA\Property(property: self::getLogoUrl, description: '网站Logo URL', type: 'string'),
        new OA\Property(property: self::getDescription, description: '网站描述', type: 'string'),
        new OA\Property(property: self::getContactEmail, description: '联系人邮箱', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序权重', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：0待审核，1已审核，2已拒绝', type: 'integer'),
    ]
)]
class FriendLinkCreateRequest extends FormRequest
{
    public const string getCategory = 'category';

    public const string getSiteName = 'siteName';

    public const string getSiteUrl = 'siteUrl';

    public const string getLogoUrl = 'logoUrl';

    public const string getDescription = 'description';

    public const string getContactEmail = 'contactEmail';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getCategory => 'required',
            self::getSiteName => 'required',
            self::getSiteUrl => 'required',
            self::getLogoUrl => 'required',
            self::getDescription => 'required',
            self::getContactEmail => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCategory.'.required' => '请设置链接分类（如：合作伙伴、友情链接）',
            self::getSiteName.'.required' => '请设置网站名称',
            self::getSiteUrl.'.required' => '请设置网站URL',
            self::getLogoUrl.'.required' => '请设置网站Logo URL',
            self::getDescription.'.required' => '请设置网站描述',
            self::getContactEmail.'.required' => '请设置联系人邮箱',
            self::getSort.'.required' => '请设置排序权重',
            self::getStatus.'.required' => '请设置状态：0待审核，1已审核，2已拒绝',
        ];
    }
}
