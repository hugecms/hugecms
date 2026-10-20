<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteAdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteAdPositionUpdateRequest',
    required: [
        self::getId,
        self::getCode,
        self::getName,
        self::getDescription,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '广告位标识,如:home_top', type: 'string'),
        new OA\Property(property: self::getName, description: '广告位名称', type: 'string'),
        new OA\Property(property: self::getDescription, description: '说明,如:首页顶部,建议尺寸750x300', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SiteAdPositionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

    public const string getName = 'name';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCode => 'required',
            self::getName => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCode.'.required' => '请设置广告位标识,如:home_top',
            self::getName.'.required' => '请设置广告位名称',
            self::getDescription.'.required' => '请设置说明,如:首页顶部,建议尺寸750x300',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
