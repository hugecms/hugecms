<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatFaq;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatFaqUpdateRequest',
    required: [
        self::getId,
        self::getCategory,
        self::getQuestion,
        self::getAnswer,
        self::getViews,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategory, description: '分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户', type: 'string'),
        new OA\Property(property: self::getQuestion, description: '问题', type: 'string'),
        new OA\Property(property: self::getAnswer, description: '答案', type: 'string'),
        new OA\Property(property: self::getViews, description: '浏览量', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class ChatFaqUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategory = 'category';

    public const string getQuestion = 'question';

    public const string getAnswer = 'answer';

    public const string getViews = 'views';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCategory => 'required',
            self::getQuestion => 'required',
            self::getAnswer => 'required',
            self::getViews => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCategory.'.required' => '请设置分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户',
            self::getQuestion.'.required' => '请设置问题',
            self::getAnswer.'.required' => '请设置答案',
            self::getViews.'.required' => '请设置浏览量',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=隐藏,1=显示',
        ];
    }
}
