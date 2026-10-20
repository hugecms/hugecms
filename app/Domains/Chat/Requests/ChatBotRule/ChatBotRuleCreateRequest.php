<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatBotRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatBotRuleCreateRequest',
    required: [
        self::getKeyword,
        self::getReplyType,
        self::getContent,
        self::getFaqId,
        self::getHitCount,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getKeyword, description: '命中关键词,消息包含即触发', type: 'string'),
        new OA\Property(property: self::getReplyType, description: '回复类型:1=文本,2=FAQ,3=转人工', type: 'integer'),
        new OA\Property(property: self::getContent, description: '回复内容,文本回复时用', type: 'string'),
        new OA\Property(property: self::getFaqId, description: 'FAQ ID,FAQ回复时用', type: 'integer'),
        new OA\Property(property: self::getHitCount, description: '命中次数,运营优化依据', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序,小者优先匹配', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class ChatBotRuleCreateRequest extends FormRequest
{
    public const string getKeyword = 'keyword';

    public const string getReplyType = 'replyType';

    public const string getContent = 'content';

    public const string getFaqId = 'faqId';

    public const string getHitCount = 'hitCount';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required',
            self::getReplyType => 'required',
            self::getContent => 'required',
            self::getFaqId => 'required',
            self::getHitCount => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请设置命中关键词,消息包含即触发',
            self::getReplyType.'.required' => '请设置回复类型:1=文本,2=FAQ,3=转人工',
            self::getContent.'.required' => '请设置回复内容,文本回复时用',
            self::getFaqId.'.required' => '请设置FAQ ID,FAQ回复时用',
            self::getHitCount.'.required' => '请设置命中次数,运营优化依据',
            self::getSort.'.required' => '请设置排序,小者优先匹配',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
