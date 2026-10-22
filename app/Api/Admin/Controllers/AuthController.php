<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Auth\LoginRequest;
use App\Api\Admin\Responses\Auth\LoginResponse;
use App\Api\Admin\Responses\Auth\MeResponse;
use App\Enums\ErrorEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use Throwable;

class AuthController extends Controller
{
    #[OA\Post(path: '/auth/login', summary: '管理员登录接口', tags: ['认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            // admin_login 为 session 驱动（仅凭据校验），token 鉴权走 admin_user（sanctum）
            $adminUser = $request->user('admin_login');
            $adminUser->forceFill([
                'last_login_time' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();

            $expiresAt = now()->addDays(7);
            $token = $adminUser->createToken('webapi', ['*'], $expiresAt);

            $loginResponse = new LoginResponse;
            $loginResponse->setToken($token->plainTextToken);
            $loginResponse->setExpires($expiresAt->timestamp);

            return $this->success($loginResponse->toArray());
        } catch (ValidationException $e) {
            // 凭据校验失败：放行渲染 422 + errors（HTTP 层 422 分支取 message 提示）
            throw $e;
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->error(ErrorEnum::LOGIN_FAIL);
        }
    }

    #[OA\Get(path: '/auth/me', summary: '获取当前管理员信息', security: [['bearerAuth' => []]], tags: ['认证模块'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MeResponse::class))]
    public function me(Request $request): JsonResponse
    {
        try {
            $adminUser = $request->user('admin_user');
            if ($adminUser === null) {
                return $this->error(ErrorEnum::UNAUTHORIZED);
            }

            $meResponse = new MeResponse;
            $meResponse->setId((int) $adminUser->id);
            $meResponse->setUsername((string) $adminUser->username);
            $meResponse->setEmail($adminUser->email !== null ? (string) $adminUser->email : null);
            $meResponse->setIsSuper((int) $adminUser->is_super);

            return $this->success($meResponse->toArray());
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->error(ErrorEnum::UNAUTHORIZED);
        }
    }

    #[OA\Post(path: '/auth/logout', summary: '管理员退出登录', security: [['bearerAuth' => []]], tags: ['认证模块'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function logout(Request $request): JsonResponse
    {
        try {
            // 吊销当前访问令牌（Sanctum）
            $request->user('admin_user')?->currentAccessToken()?->delete();

            return $this->success();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->error(ErrorEnum::LOGOUT_FAIL);
        }
    }
}
