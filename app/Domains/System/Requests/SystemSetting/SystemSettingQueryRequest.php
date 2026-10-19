<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSetting;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSettingQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGroup, description: '配置分组:basic=基础,seo,upload', type: 'string'),
        new OA\Property(property: self::getKey, description: '配置键,如:site_name', type: 'string'),
    ]
)]
class SystemSettingQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getGroup = 'group';

    public const string getKey = 'key';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
