<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommentUpdateRequest',
    required: [
        self::getId,
        self::getContentId,
        self::getUserId,
        self::getParentId,
        self::getReplyToUserId,
        self::getAuthorName,
        self::getAuthorEmail,
        self::getAuthorUrl,
        self::getContent,
        self::getIp,
        self::getUserAgent,
        self::getStatus,
        self::getLikeCount,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '关联内容主表ID（全模型通用）', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '评论者用户ID（NULL表示游客）', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父评论ID（0=顶级评论，支持楼中楼）', type: 'integer'),
        new OA\Property(property: self::getReplyToUserId, description: '被回复用户ID（渲染&quot;回复@xxx&quot;用）', type: 'integer'),
        new OA\Property(property: self::getAuthorName, description: '评论者昵称（游客填写；登录用户冗余，防销号后无记录）', type: 'string'),
        new OA\Property(property: self::getAuthorEmail, description: '评论者邮箱（游客填写，用于头像/回复通知）', type: 'string'),
        new OA\Property(property: self::getAuthorUrl, description: '评论者主页URL', type: 'string'),
        new OA\Property(property: self::getContent, description: '评论内容（纯文本；敏感词/反垃圾由插件钩子处理）', type: 'string'),
        new OA\Property(property: self::getIp, description: '评论者IP（反垃圾由插件处理）', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: '评论者UA', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：pending待审核/approved已通过/spam垃圾/trash回收站', type: 'string'),
        new OA\Property(property: self::getLikeCount, description: '点赞数', type: 'integer'),
    ]
)]
class CommentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getUserId = 'userId';

    public const string getParentId = 'parentId';

    public const string getReplyToUserId = 'replyToUserId';

    public const string getAuthorName = 'authorName';

    public const string getAuthorEmail = 'authorEmail';

    public const string getAuthorUrl = 'authorUrl';

    public const string getContent = 'content';

    public const string getIp = 'ip';

    public const string getUserAgent = 'userAgent';

    public const string getStatus = 'status';

    public const string getLikeCount = 'likeCount';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getContentId => 'required',
            self::getUserId => 'required',
            self::getParentId => 'required',
            self::getReplyToUserId => 'required',
            self::getAuthorName => 'required',
            self::getAuthorEmail => 'required',
            self::getAuthorUrl => 'required',
            self::getContent => 'required',
            self::getIp => 'required',
            self::getUserAgent => 'required',
            self::getStatus => 'required',
            self::getLikeCount => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getContentId.'.required' => '请设置关联内容主表ID（全模型通用）',
            self::getUserId.'.required' => '请设置评论者用户ID（NULL表示游客）',
            self::getParentId.'.required' => '请设置父评论ID（0=顶级评论，支持楼中楼）',
            self::getReplyToUserId.'.required' => '请设置被回复用户ID（渲染&quot;回复@xxx&quot;用）',
            self::getAuthorName.'.required' => '请设置评论者昵称（游客填写；登录用户冗余，防销号后无记录）',
            self::getAuthorEmail.'.required' => '请设置评论者邮箱（游客填写，用于头像/回复通知）',
            self::getAuthorUrl.'.required' => '请设置评论者主页URL',
            self::getContent.'.required' => '请设置评论内容（纯文本；敏感词/反垃圾由插件钩子处理）',
            self::getIp.'.required' => '请设置评论者IP（反垃圾由插件处理）',
            self::getUserAgent.'.required' => '请设置评论者UA',
            self::getStatus.'.required' => '请设置状态：pending待审核/approved已通过/spam垃圾/trash回收站',
            self::getLikeCount.'.required' => '请设置点赞数',
        ];
    }
}
