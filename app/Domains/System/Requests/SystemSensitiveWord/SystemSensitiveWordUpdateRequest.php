<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSensitiveWord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSensitiveWordUpdateRequest',
    required: [
        self::getId,
        self::getWord,
        self::getReplacement,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getWord, description: '敏感词', type: 'string'),
        new OA\Property(property: self::getReplacement, description: '替换词,空=直接拦截', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SystemSensitiveWordUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getWord = 'word';

    public const string getReplacement = 'replacement';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getWord => 'required',
            self::getReplacement => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getWord.'.required' => '请设置敏感词',
            self::getReplacement.'.required' => '请设置替换词,空=直接拦截',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
