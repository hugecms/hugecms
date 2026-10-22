<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * 管理端鉴权：admin_user（Sanctum Bearer）校验
 * 组级挂载于 admin 路由组，登录路径豁免；未认证返回 envelope code=401（HTTP 200，与前端 request.ts 约定一致）
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        // 登录接口豁免（生成文件无法逐路由挂中间件，组级 + 路径豁免是不改生成器的最小方案）
        if ($request->is('api/admin/auth/login')) {
            return $next($request);
        }

        if ($request->user('admin_user') === null) {
            return response()->json([
                'code' => 401,
                'message' => '未登录或登录已过期',
                'data' => null,
            ]);
        }

        return $next($request);
    }
}
