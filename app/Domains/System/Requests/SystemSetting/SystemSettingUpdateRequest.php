<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSetting;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSettingUpdateRequest',
    required: [
        self::getId,
        self::getGroup,
        self::getKey,
        self::getTitle,
        self::getValue,
        self::getType,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGroup, description: '配置分组:basic=基础,seo,upload', type: 'string'),
        new OA\Property(property: self::getKey, description: '配置键,如:site_name', type: 'string'),
        new OA\Property(property: self::getTitle, description: '配置名称,用于后台表单展示', type: 'string'),
        new OA\Property(property: self::getValue, description: '配置值', type: 'string'),
        new OA\Property(property: self::getType, description: '值类型:text,number,select,radio', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class SystemSettingUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getGroup = 'group';

    public const string getKey = 'key';

    public const string getTitle = 'title';

    public const string getValue = 'value';

    public const string getType = 'type';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getGroup => 'required',
            self::getKey => 'required',
            self::getTitle => 'required',
            self::getValue => 'required',
            self::getType => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getGroup.'.required' => '请设置配置分组:basic=基础,seo,upload',
            self::getKey.'.required' => '请设置配置键,如:site_name',
            self::getTitle.'.required' => '请设置配置名称,用于后台表单展示',
            self::getValue.'.required' => '请设置配置值',
            self::getType.'.required' => '请设置值类型:text,number,select,radio',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
