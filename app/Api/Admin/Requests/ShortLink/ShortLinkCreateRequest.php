<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ShortLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShortLinkCreateRequest',
    required: [
        self::getShortCode,
        self::getTargetUrl,
        self::getTitle,
        self::getClickCount,
        self::getQrCodePath,
        self::getExpireAt,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getShortCode, description: '短链代码（如：abc123）', type: 'string'),
        new OA\Property(property: self::getTargetUrl, description: '原始目标URL', type: 'string'),
        new OA\Property(property: self::getTitle, description: '链接标题/备注', type: 'string'),
        new OA\Property(property: self::getClickCount, description: '点击次数', type: 'integer'),
        new OA\Property(property: self::getQrCodePath, description: '二维码图片存储路径', type: 'string'),
        new OA\Property(property: self::getExpireAt, description: '过期时间（NULL永不过期）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class ShortLinkCreateRequest extends FormRequest
{
    public const string getShortCode = 'shortCode';

    public const string getTargetUrl = 'targetUrl';

    public const string getTitle = 'title';

    public const string getClickCount = 'clickCount';

    public const string getQrCodePath = 'qrCodePath';

    public const string getExpireAt = 'expireAt';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getShortCode => 'required',
            self::getTargetUrl => 'required',
            self::getTitle => 'required',
            self::getClickCount => 'required',
            self::getQrCodePath => 'required',
            self::getExpireAt => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShortCode.'.required' => '请设置短链代码（如：abc123）',
            self::getTargetUrl.'.required' => '请设置原始目标URL',
            self::getTitle.'.required' => '请设置链接标题/备注',
            self::getClickCount.'.required' => '请设置点击次数',
            self::getQrCodePath.'.required' => '请设置二维码图片存储路径',
            self::getExpireAt.'.required' => '请设置过期时间（NULL永不过期）',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
