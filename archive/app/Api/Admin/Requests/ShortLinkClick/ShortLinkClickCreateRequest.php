<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ShortLinkClick;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShortLinkClickCreateRequest',
    required: [
        self::getShortLinkId,
        self::getClickIp,
        self::getUserAgent,
        self::getReferer,
    ],
    properties: [
        new OA\Property(property: self::getShortLinkId, description: '短链接ID', type: 'integer'),
        new OA\Property(property: self::getClickIp, description: '点击者IP', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: '浏览器UA', type: 'string'),
        new OA\Property(property: self::getReferer, description: '来源页', type: 'string'),
    ]
)]
class ShortLinkClickCreateRequest extends FormRequest
{
    public const string getShortLinkId = 'shortLinkId';

    public const string getClickIp = 'clickIp';

    public const string getUserAgent = 'userAgent';

    public const string getReferer = 'referer';

    public function rules(): array
    {
        return [
            self::getShortLinkId => 'required',
            self::getClickIp => 'required',
            self::getUserAgent => 'required',
            self::getReferer => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShortLinkId.'.required' => '请设置短链接ID',
            self::getClickIp.'.required' => '请设置点击者IP',
            self::getUserAgent.'.required' => '请设置浏览器UA',
            self::getReferer.'.required' => '请设置来源页',
        ];
    }
}
