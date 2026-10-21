<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRecordAppealRequest',
    required: [
        self::getId,
        self::getPass,
    ],
    properties: [
        new OA\Property(property: self::getId, description: '风控事件ID', type: 'integer'),
        new OA\Property(property: self::getPass, description: '申诉是否通过:true=通过解除拦截,false=驳回', type: 'boolean'),
        new OA\Property(property: self::getHandleNote, description: '申诉处理说明', type: 'string'),
    ]
)]
class SystemRiskRecordAppealRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPass = 'pass';

    public const string getHandleNote = 'handleNote';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer|min:1',
            self::getPass => 'required|boolean',
            self::getHandleNote => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置风控事件ID',
            self::getPass.'.required' => '请设置申诉是否通过',
        ];
    }
}
