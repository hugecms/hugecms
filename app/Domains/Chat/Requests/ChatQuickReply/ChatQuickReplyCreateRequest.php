<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatQuickReply;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatQuickReplyCreateRequest',
    required: [
        self::getGroup,
        self::getTitle,
        self::getContent,
        self::getUseCount,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getGroup, description: '分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流', type: 'string'),
        new OA\Property(property: self::getTitle, description: '话术标题', type: 'string'),
        new OA\Property(property: self::getContent, description: '话术内容', type: 'string'),
        new OA\Property(property: self::getUseCount, description: '使用次数', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class ChatQuickReplyCreateRequest extends FormRequest
{
    public const string getGroup = 'group';

    public const string getTitle = 'title';

    public const string getContent = 'content';

    public const string getUseCount = 'useCount';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getGroup => 'required',
            self::getTitle => 'required',
            self::getContent => 'required',
            self::getUseCount => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getGroup.'.required' => '请设置分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流',
            self::getTitle.'.required' => '请设置话术标题',
            self::getContent.'.required' => '请设置话术内容',
            self::getUseCount.'.required' => '请设置使用次数',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
