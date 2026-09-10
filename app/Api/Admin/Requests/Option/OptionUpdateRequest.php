<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Option;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OptionUpdateRequest',
    required: [
        self::getId,
        self::getOptionKey,
        self::getOptionValue,
        self::getAutoload,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOptionKey, description: '配置键名（storage_config/smtp_config/comment_config等）', type: 'string'),
        new OA\Property(property: self::getOptionValue, description: '配置值（支持JSON复杂结构）', type: 'string'),
        new OA\Property(property: self::getAutoload, description: '启动时自动加载：0否，1是（配合 Laravel Cache 预热）', type: 'integer'),
    ]
)]
class OptionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOptionKey = 'optionKey';

    public const string getOptionValue = 'optionValue';

    public const string getAutoload = 'autoload';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOptionKey => 'required',
            self::getOptionValue => 'required',
            self::getAutoload => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOptionKey.'.required' => '请设置配置键名（storage_config/smtp_config/comment_config等）',
            self::getOptionValue.'.required' => '请设置配置值（支持JSON复杂结构）',
            self::getAutoload.'.required' => '请设置启动时自动加载：0否，1是（配合 Laravel Cache 预热）',
        ];
    }
}
