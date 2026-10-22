<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentComment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCommentCreateRequest',
    required: [
        self::getUserId,
        self::getTargetType,
        self::getTargetId,
        self::getParentId,
        self::getContent,
        self::getStatus,
        self::getIp,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '评论用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article=文章;商品评价走product_reviews表', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父评论ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getContent, description: '评论内容', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审,1=通过,2=拒绝', type: 'integer'),
        new OA\Property(property: self::getIp, description: '评论IP', type: 'string'),
    ]
)]
class ContentCommentCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getParentId = 'parentId';

    public const string getContent = 'content';

    public const string getStatus = 'status';

    public const string getIp = 'ip';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getParentId => 'required',
            self::getContent => 'required',
            self::getStatus => 'required',
            self::getIp => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置评论用户ID',
            self::getTargetType.'.required' => '请设置目标类型:article=文章;商品评价走product_reviews表',
            self::getTargetId.'.required' => '请设置目标ID',
            self::getParentId.'.required' => '请设置父评论ID,0=顶级',
            self::getContent.'.required' => '请设置评论内容',
            self::getStatus.'.required' => '请设置状态:0=待审,1=通过,2=拒绝',
            self::getIp.'.required' => '请设置评论IP',
        ];
    }
}
