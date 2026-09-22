<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentPushQueue;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentPushQueueUpdateRequest',
    required: [
        self::getId,
        self::getContentId,
        self::getPushType,
        self::getPushData,
        self::getStatus,
        self::getRetryCount,
        self::getMaxRetries,
        self::getErrorMessage,
        self::getFinishedAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '被推送的内容ID', type: 'integer'),
        new OA\Property(property: self::getPushType, description: '推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss', type: 'string'),
        new OA\Property(property: self::getPushData, description: '推送数据的最终形态（预处理后JSON）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：pending/processing/success/failed（执行与重试走 Laravel 队列）', type: 'string'),
        new OA\Property(property: self::getRetryCount, description: '已重试次数', type: 'integer'),
        new OA\Property(property: self::getMaxRetries, description: '最大重试次数', type: 'integer'),
        new OA\Property(property: self::getErrorMessage, description: '失败时的错误信息', type: 'string'),
        new OA\Property(property: self::getFinishedAt, description: '完成时间', type: 'string'),
    ]
)]
class ContentPushQueueUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getPushType = 'pushType';

    public const string getPushData = 'pushData';

    public const string getStatus = 'status';

    public const string getRetryCount = 'retryCount';

    public const string getMaxRetries = 'maxRetries';

    public const string getErrorMessage = 'errorMessage';

    public const string getFinishedAt = 'finishedAt';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getContentId => 'required',
            self::getPushType => 'required',
            self::getPushData => 'required',
            self::getStatus => 'required',
            self::getRetryCount => 'required',
            self::getMaxRetries => 'required',
            self::getErrorMessage => 'required',
            self::getFinishedAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getContentId.'.required' => '请设置被推送的内容ID',
            self::getPushType.'.required' => '请设置推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss',
            self::getPushData.'.required' => '请设置推送数据的最终形态（预处理后JSON）',
            self::getStatus.'.required' => '请设置状态：pending/processing/success/failed（执行与重试走 Laravel 队列）',
            self::getRetryCount.'.required' => '请设置已重试次数',
            self::getMaxRetries.'.required' => '请设置最大重试次数',
            self::getErrorMessage.'.required' => '请设置失败时的错误信息',
            self::getFinishedAt.'.required' => '请设置完成时间',
        ];
    }
}
