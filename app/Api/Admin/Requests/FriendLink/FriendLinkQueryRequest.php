<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\FriendLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FriendLinkQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getCategory, description: '链接分类（如：合作伙伴、友情链接）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0待审核，1已审核，2已拒绝', type: 'integer'),
    ]
)]
class FriendLinkQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getCategory = 'category';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
