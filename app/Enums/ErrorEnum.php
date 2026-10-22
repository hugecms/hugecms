<?php

declare(strict_types=1);

namespace App\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

enum ErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 用户不存在
     */
    case USER_NOT_EXIST = 10404;

    /**
     * 登录失败
     */
    case LOGIN_FAIL = 10501;

    /**
     * 未登录或登录已过期（前端约定 envelope code===401 时清登录态并跳登录页）
     */
    case UNAUTHORIZED = 401;

    /**
     * 退出登录失败
     */
    case LOGOUT_FAIL = 10502;
}
