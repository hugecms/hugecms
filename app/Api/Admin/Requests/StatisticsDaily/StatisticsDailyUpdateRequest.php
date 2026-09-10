<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\StatisticsDaily;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StatisticsDailyUpdateRequest',
    required: [
        self::getId,
        self::getStatDate,
        self::getNewContents,
        self::getPublishedContents,
        self::getTotalContents,
        self::getTotalViews,
        self::getNewComments,
        self::getNewUsers,
        self::getActiveUsers,
        self::getTotalUsers,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStatDate, description: '统计日期', type: 'string'),
        new OA\Property(property: self::getNewContents, description: '新增内容数', type: 'integer'),
        new OA\Property(property: self::getPublishedContents, description: '发布内容数', type: 'integer'),
        new OA\Property(property: self::getTotalContents, description: '累计内容总数', type: 'integer'),
        new OA\Property(property: self::getTotalViews, description: '全站浏览量', type: 'integer'),
        new OA\Property(property: self::getNewComments, description: '新增评论数', type: 'integer'),
        new OA\Property(property: self::getNewUsers, description: '新增注册用户数', type: 'integer'),
        new OA\Property(property: self::getActiveUsers, description: '活跃用户数（登录/操作）', type: 'integer'),
        new OA\Property(property: self::getTotalUsers, description: '累计注册用户数', type: 'integer'),
    ]
)]
class StatisticsDailyUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getStatDate = 'statDate';

    public const string getNewContents = 'newContents';

    public const string getPublishedContents = 'publishedContents';

    public const string getTotalContents = 'totalContents';

    public const string getTotalViews = 'totalViews';

    public const string getNewComments = 'newComments';

    public const string getNewUsers = 'newUsers';

    public const string getActiveUsers = 'activeUsers';

    public const string getTotalUsers = 'totalUsers';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getStatDate => 'required',
            self::getNewContents => 'required',
            self::getPublishedContents => 'required',
            self::getTotalContents => 'required',
            self::getTotalViews => 'required',
            self::getNewComments => 'required',
            self::getNewUsers => 'required',
            self::getActiveUsers => 'required',
            self::getTotalUsers => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getStatDate.'.required' => '请设置统计日期',
            self::getNewContents.'.required' => '请设置新增内容数',
            self::getPublishedContents.'.required' => '请设置发布内容数',
            self::getTotalContents.'.required' => '请设置累计内容总数',
            self::getTotalViews.'.required' => '请设置全站浏览量',
            self::getNewComments.'.required' => '请设置新增评论数',
            self::getNewUsers.'.required' => '请设置新增注册用户数',
            self::getActiveUsers.'.required' => '请设置活跃用户数（登录/操作）',
            self::getTotalUsers.'.required' => '请设置累计注册用户数',
        ];
    }
}
