<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemDict;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemDictCreateRequest',
    required: [
        self::getType,
        self::getName,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '类型标识,如:gender', type: 'string'),
        new OA\Property(property: self::getName, description: '类型名称', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SystemDictCreateRequest extends FormRequest
{
    public const string getType = 'type';

    public const string getName = 'name';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getType => 'required',
            self::getName => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请设置类型标识,如:gender',
            self::getName.'.required' => '请设置类型名称',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
