# Portal 认证模块实现计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 在 `app/Modules/Portal` 模块实现完整的 Session 认证流程：登录（含记住我/限流/intended 跳转）、登出、开放注册（自动登录）、忘记密码、重置密码。

**Architecture:** 全部手写原生 Laravel Auth（无新依赖）。控制器放 `app/Modules/Portal/Controllers`，校验放 `app/Modules/Portal/Requests`（沿用 `const string` 字段 + 中文 messages 约定），视图放 `app/Modules/Portal/Views`（Blade + Bootstrap 5 静态资产 + 渐进增强 Vue）。路由由 `php artisan gen:route` 从控制器首个 OA 属性反射生成；中间件用控制器 `HasMiddleware` 声明（生成工具不支持路由级中间件）。

**Tech Stack:** Laravel 13（Session Guard、Password Broker、RateLimiter）、Blade、Bootstrap 5.3.8、Vue 3（仅密码显隐切换）、Pest 5。

**Spec:** `docs/superpowers/specs/2026-08-25-portal-auth-design.md`

## Global Constraints

- 不新增任何 composer/npm 依赖
- 不修改 `app/Http/Middleware/Auth.php`（RBAC 授权预留）
- 不实现 `MustVerifyEmail`（不做邮箱验证）
- 路由注册只能通过控制器 OA 属性 + `php artisan gen:route` 重新生成；**禁止手改 `route.gen.php`**（文件头标注 DO NOT EDIT）
- 认证用内置 `auth` 中间件，游客拦截用 `guest`；两者经控制器 `HasMiddleware::middleware()` 声明
- 登录失败提示统一模糊文案「邮箱或密码错误」
- 每个 PHP 文件：`declare(strict_types=1);` + 显式参数/返回类型 + PHPDoc（不用行内注释）
- 每个任务完成后运行 `vendor/bin/pint --dirty --format agent`
- 测试用 Pest 语法（`test()`、`uses(RefreshDatabase::class)`、`expect()`）
- 与 spec 的两处已批准偏差（Task 1 会同步修订 spec）：
  1. 路由名由 gen:route 自动派生：`forgot-password`、`reset-password`（非 Laravel 惯例的 `password.request`/`password.reset`）；重置 token 经查询串 `?token=…&email=…` 传入（路径参数会生成含 `{token}` 的畸形路由名）
  2. 登录后跳转 `redirect()->intended('/')`（非 `route('index')`——`index` 路由名被 Admin/Portal/User 三模块重复注册，解析结果不确定）
- 由于路由名不是 `password.reset`，框架默认重置邮件链接会抛 `RouteNotFoundException`，必须在 `AppServiceProvider` 用 `ResetPassword::createUrlUsing()` 覆盖（Task 1）

## 文件结构总览

```
修改：
  bootstrap/app.php                                  # guest/user 重定向配置
  app/Providers/AppServiceProvider.php               # 两个限流器 + ResetPassword 链接生成
  tests/Pest.php                                     # 启用 RefreshDatabase
  app/Modules/Portal/Controllers/AuthController.php  # 重写
  app/Modules/Portal/Views/layout.blade.php          # 增强共享布局
  app/Modules/Portal/Views/login.blade.php           # 重写
  app/Modules/Portal/Routes/route.gen.php            # gen:route 再生成（自动）
  docs/superpowers/specs/2026-08-25-portal-auth-design.md  # 修订偏差
创建：
  app/Modules/Portal/Requests/LoginRequest.php
  app/Modules/Portal/Requests/RegisterRequest.php
  app/Modules/Portal/Requests/ForgotPasswordRequest.php
  app/Modules/Portal/Requests/ResetPasswordRequest.php
  app/Modules/Portal/Controllers/RegisterController.php
  app/Modules/Portal/Controllers/PasswordResetController.php
  app/Modules/Portal/Views/register.blade.php
  app/Modules/Portal/Views/forgot-password.blade.php
  app/Modules/Portal/Views/reset-password.blade.php
  tests/Feature/Auth/AuthenticationTest.php
  tests/Feature/Auth/RegistrationTest.php
  tests/Feature/Auth/PasswordResetTest.php
  tests/Feature/Auth/LoginRateLimitTest.php
```

---

### Task 1: 全局配置与 spec 修订

**Files:**
- Modify: `docs/superpowers/specs/2026-08-25-portal-auth-design.md`
- Modify: `tests/Pest.php`
- Modify: `bootstrap/app.php`
- Modify: `app/Providers/AppServiceProvider.php`

**Interfaces:**
- Produces: 限流器名 `login`（键 `email|ip`，5 次/分钟）与 `auth`（键 `ip`，5 次/分钟）——Task 3/4/5/6 的控制器与中间件引用这两个名字
- Produces: `ResetPassword::createUrlUsing` 生成 `/reset-password?token=…&email=…` 链接——Task 5 发送邮件、Task 6 页面读取查询串依赖此格式

- [ ] **Step 1: 修订 spec 的两处偏差**

在 `docs/superpowers/specs/2026-08-25-portal-auth-design.md` 中：

a) 路由表四行替换（`password.request`/`password.reset` 行）：

找到：

```markdown
| GET | /forgot-password | PasswordResetController@request | `password.request` | guest |
| POST | /forgot-password | PasswordResetController@email | — | guest + throttle:auth |
| GET | /reset-password/{token} | PasswordResetController@edit | `password.reset` | guest |
| POST | /reset-password | PasswordResetController@update | — | guest + throttle:auth |
```

替换为：

```markdown
| GET | /forgot-password | PasswordResetController@request | `forgot-password` | guest |
| POST | /forgot-password | PasswordResetController@email | — | guest + throttle:auth |
| GET | /reset-password | PasswordResetController@edit | `reset-password` | guest |
| POST | /reset-password | PasswordResetController@update | — | guest + throttle:auth |
```

b) 路由表下方的「中间件配置」小节末尾追加一行：

```markdown
- `ResetPassword::createUrlUsing()`（AppServiceProvider）：因路由名非 `password.reset`，须覆盖默认重置链接为 `/reset-password?token=…&email=…`
- 登录跳转 `redirect()->intended('/')`：`index` 路由名被 Admin/Portal/User 三模块重复注册，不可用 `route('index')`
```

- [ ] **Step 2: 启用 RefreshDatabase**

`tests/Pest.php` 中找到：

```php
pest()->extend(TestCase::class)
 // ->use(RefreshDatabase::class)
    ->in('Feature');
```

替换为：

```php
pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');
```

- [ ] **Step 3: 配置 guest/user 重定向**

`bootstrap/app.php` 中找到：

```php
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
```

替换为：

```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo('/');
    })
```

- [ ] **Step 4: 注册限流器与重置链接生成**

`app/Providers/AppServiceProvider.php` 整文件替换为：

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadModules();

        // 登录限流：按邮箱+IP，每分钟 5 次（仅计失败，控制器手动调用）
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(Str::lower($request->string('email')).'|'.$request->ip());
        });

        // 认证类接口限流：按 IP，每分钟 5 次（注册/忘记密码/重置密码）
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 路由名为 reset-password（非框架默认的 password.reset），覆盖默认重置链接
        ResetPassword::createUrlUsing(function (CanResetPassword $notifiable, string $token) {
            return url('/reset-password?token='.$token.'&email='.urlencode($notifiable->getEmailForPasswordReset()));
        });
    }

    private function loadModules(): void
    {
        $modules = glob(app_path('Modules/*'), GLOB_ONLYDIR);
        foreach ($modules as $module) {
            $namespace = Str::lower(basename($module));
            View::addNamespace($namespace, $module.'/Views');
        }
    }
}
```

- [ ] **Step 5: 验证基线测试通过**

Run: `php artisan test --compact`
Expected: PASS（ExampleTest 两个，不触库）

- [ ] **Step 6: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add bootstrap/app.php app/Providers/AppServiceProvider.php tests/Pest.php docs/superpowers/specs/2026-08-25-portal-auth-design.md
git commit -m "chore: 认证模块全局配置（重定向、限流器、重置链接、RefreshDatabase）"
```

---

### Task 2: 登录页面与共享布局

**Files:**
- Modify: `app/Modules/Portal/Views/layout.blade.php`（整文件重写）
- Modify: `app/Modules/Portal/Views/login.blade.php`（整文件重写）
- Test: `tests/Feature/Auth/AuthenticationTest.php`（本任务只含第一个测试）

**Interfaces:**
- Produces: 共享布局契约——子页面用 `@extends('portal::layout')` + `@section('title')` + `@section('content')` + 可选 `@section('scripts')`；布局负责 Bootstrap 资产、Flash 提示条（`session('status')`）。Task 4/5/6 的视图依赖此契约
- Produces: 布局保留 `@yield('content')`，现有 `index.blade.php` 不受影响

- [ ] **Step 1: 创建测试文件并写第一个失败测试**

```bash
php artisan make:test --pest --no-interaction Auth/AuthenticationTest
```

写入 `tests/Feature/Auth/AuthenticationTest.php`：

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('登录页面可以正常显示', function () {
    $this->get('/login')->assertOk()->assertSee('登录 HugeCMS');
});
```

（`User` 导入与后续测试共用，暂时未用会在后续任务用到，勿删。）

- [ ] **Step 2: 运行测试确认失败**

Run: `php artisan test --compact --filter=登录页面可以正常显示`
Expected: FAIL——占位页只有「login page」文本，`assertSee('登录 HugeCMS')` 不成立

- [ ] **Step 3: 重写共享布局**

`app/Modules/Portal/Views/layout.blade.php` 整文件替换为：

```blade
<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HugeCMS')</title>
    <link href="{{ asset('static/bootstrap-5.3.8/css/bootstrap.min.css') }}" rel="stylesheet" />
</head>
<body class="bg-body-secondary">
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
    </div>
@endif
@yield('content')
<script src="{{ asset('static/jquery-4.0.0/jquery.min.js') }}"></script>
<script src="{{ asset('static/bootstrap-5.3.8/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('static/vue-3.5.41/vue.global.prod.js') }}"></script>
@yield('scripts')
</body>
</html>
```

- [ ] **Step 4: 重写登录视图**

`app/Modules/Portal/Views/login.blade.php` 整文件替换为：

```blade
@extends('portal::layout')

@section('title', '登录 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">登录 HugeCMS</h1>
                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">密码</label>
                        <div class="input-group" id="password-toggle">
                            <input type="password" id="password" name="password" ref="passwordInput"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" ref="toggleBtn" @click="togglePassword"
                                    class="btn btn-outline-secondary">显示</button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">记住我</label>
                        </div>
                        <a href="{{ route('forgot-password') }}" class="small">忘记密码？</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">登录</button>
                </form>

                <p class="text-center small mt-3 mb-0">
                    还没有账号？<a href="{{ route('register') }}">注册账号</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const passwordInput = ref(null);
                const toggleBtn = ref(null);
                const togglePassword = () => {
                    const input = passwordInput.value;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    toggleBtn.value.textContent = input.type === 'password' ? '显示' : '隐藏';
                };
                return { passwordInput, toggleBtn, togglePassword };
            },
        }).mount('#password-toggle');
    </script>
@endsection
```

注意：`route('forgot-password')` 与 `route('register')` 本任务尚不存在（Task 4/5 才生成）——渲染会抛 RouteNotFoundException。**临时**把两个链接的 `href` 先写为 `/forgot-password` 与 `/register` 字面量，Task 4、5 完成后由该任务的最后一步分别替换回 `{{ route(...) }}`。（若跳过此临时处理，本任务测试会失败。）

- [ ] **Step 5: 运行测试确认通过**

Run: `php artisan test --compact --filter=登录页面可以正常显示`
Expected: PASS

Run: `php artisan test --compact`
Expected: 全部 PASS（含 `/` 首页 ExampleTest，验证布局改动不破坏现有页面）

- [ ] **Step 6: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add app/Modules/Portal/Views/layout.blade.php app/Modules/Portal/Views/login.blade.php tests/Feature/Auth/AuthenticationTest.php
git commit -m "feat(portal): 登录页面与共享布局"
```

---

### Task 3: 登录、登出与限流

**Files:**
- Create: `app/Modules/Portal/Requests/LoginRequest.php`
- Modify: `app/Modules/Portal/Controllers/AuthController.php`（整文件重写）
- Modify: `app/Modules/Portal/Routes/route.gen.php`（经 `php artisan gen:route` 自动生成）
- Test: `tests/Feature/Auth/AuthenticationTest.php`（追加）、`tests/Feature/Auth/LoginRateLimitTest.php`

**Interfaces:**
- Consumes: 限流器 `login`（Task 1）；布局契约（Task 2）
- Produces: `LoginRequest::authenticate(): void`（含限流判定 + `Auth::attempt` + session regenerate 前置失败抛 `ValidationException`）
- Produces: 路由 `POST /login`、`POST /logout`（gen:route 自动生成，无名字）
- Produces: `AuthController` 中间件声明：`guest`（except `logout`）+ `auth`（only `logout`）

- [ ] **Step 1: 追加失败测试（AuthenticationTest）**

在 `tests/Feature/Auth/AuthenticationTest.php` 末尾追加：

```php
test('用户可以使用正确凭据登录', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/');
});

test('勾选记住我时设置记住令牌', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'remember' => true,
    ]);

    expect($user->fresh()->remember_token)->not->toBeNull();
});

test('用户不能使用错误密码登录', function () {
    $user = User::factory()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response->assertRedirect('/login')
        ->assertSessionHasErrors('email');
});

test('游客不能退出登录', function () {
    $this->post('/logout')->assertRedirect('/login');
});

test('用户可以退出登录', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/login');
});
```

- [ ] **Step 2: 创建限流测试**

```bash
php artisan make:test --pest --no-interaction Auth/LoginRateLimitTest
```

写入 `tests/Feature/Auth/LoginRateLimitTest.php`：

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('连续五次登录失败后触发限流', function () {
    $user = User::factory()->create();
    $payload = ['email' => $user->email, 'password' => 'wrong-password'];

    foreach (range(1, 5) as $attempt) {
        $this->from('/login')->post('/login', $payload);
    }

    $response = $this->from('/login')->post('/login', $payload);

    $response->assertSessionHasErrors(['email' => '尝试次数过多，请 60 秒后重试']);
    $key = Str::transliterate(Str::lower($user->email).'|127.0.0.1');
    expect(RateLimiter::attempts($key))->toBe(5);
});
```

- [ ] **Step 3: 运行测试确认失败**

Run: `php artisan test --compact --filter=AuthenticationTest`
Expected: FAIL——`POST /login`、`POST /logout` 路由不存在（404 或重定向断言失败）

- [ ] **Step 4: 编写 LoginRequest**

创建 `app/Modules/Portal/Requests/LoginRequest.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public const string getEmail = 'email';

    public const string getPassword = 'password';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['required', 'email'],
            self::getPassword => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getPassword.'.required' => '请输入密码',
        ];
    }

    /**
     * 尝试登录（含限流保护，失败抛 ValidationException）
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(self::getEmail, self::getPassword), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                self::getEmail => '邮箱或密码错误',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * 确保未触发登录限流
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            self::getEmail => "尝试次数过多，请 {$seconds} 秒后重试",
        ]);
    }

    /**
     * 限流键：邮箱|IP
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string(self::getEmail)).'|'.$this->ip());
    }
}
```

- [ ] **Step 5: 重写 AuthController**

`app/Modules/Portal/Controllers/AuthController.php` 整文件替换为：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Modules\Portal\Requests\LoginRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends BaseController implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    #[OA\Get(path: '/login', summary: '登录页面', tags: ['模块'])]
    public function login(): Renderable
    {
        return view('portal::login');
    }

    #[OA\Post(path: '/login', summary: '提交登录', tags: ['模块'])]
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    #[OA\Post(path: '/logout', summary: '退出登录', tags: ['模块'])]
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
```

- [ ] **Step 6: 重新生成路由并核对**

```bash
php artisan gen:route
git diff app/Modules/Portal/Routes/route.gen.php
```

Expected: Portal 的 route.gen.php 新增 `Route::post('login', ...)` 与 `Route::post('logout', ...)` 两行；**其他模块的 route.gen.php 无变化**（若有意外变化，停下检查 gen:route 输出后再继续）

Run: `php artisan route:list --path=login` 与 `php artisan route:list --path=logout`
Expected: POST /login、POST /logout 均出现

- [ ] **Step 7: 运行测试确认通过**

Run: `php artisan test --compact --filter=AuthenticationTest`
Expected: PASS（6 个）
Run: `php artisan test --compact --filter=LoginRateLimitTest`
Expected: PASS（1 个）

- [ ] **Step 8: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add app/Modules/Portal/Requests/LoginRequest.php app/Modules/Portal/Controllers/AuthController.php app/Modules/Portal/Routes/route.gen.php tests/Feature/Auth/
git commit -m "feat(portal): 登录、登出与限流"
```

---

### Task 4: 用户注册

**Files:**
- Create: `app/Modules/Portal/Requests/RegisterRequest.php`
- Create: `app/Modules/Portal/Controllers/RegisterController.php`
- Create: `app/Modules/Portal/Views/register.blade.php`
- Modify: `app/Modules/Portal/Routes/route.gen.php`（gen:route 自动）
- Modify: `app/Modules/Portal/Views/login.blade.php`（链接改回命名路由）
- Test: `tests/Feature/Auth/RegistrationTest.php`

**Interfaces:**
- Consumes: 布局契约（Task 2）、限流器 `auth`（Task 1）、`User::create`（`password` 由模型 hashed cast 自动哈希）
- Produces: 路由 `GET /register`（名 `register`）、`POST /register`
- Produces: 注册成功即 `Auth::attempt` + session regenerate + 跳 `/`

- [ ] **Step 1: 创建失败测试**

```bash
php artisan make:test --pest --no-interaction Auth/RegistrationTest
```

写入 `tests/Feature/Auth/RegistrationTest.php`：

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('注册页面可以正常显示', function () {
    $this->get('/register')->assertOk()->assertSee('注册账号');
});

test('用户可以注册并自动登录', function () {
    $response = $this->post('/register', [
        'name' => '测试用户',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'name' => '测试用户']);
    $response->assertRedirect('/');
});

test('重复邮箱不能注册', function () {
    $user = User::factory()->create();

    $response = $this->from('/register')->post('/register', [
        'name' => '测试用户',
        'email' => $user->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['name' => '测试用户']);
    $response->assertSessionHasErrors('email');
});

test('弱密码不能注册', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => '测试用户',
        'email' => 'test@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});
```

- [ ] **Step 2: 运行测试确认失败**

Run: `php artisan test --compact --filter=RegistrationTest`
Expected: FAIL——`/register` 路由不存在

- [ ] **Step 3: 编写 RegisterRequest**

创建 `app/Modules/Portal/Requests/RegisterRequest.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getPassword = 'password';

    /**
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:255'],
            self::getEmail => ['required', 'email', 'max:255', 'unique:users,email'],
            self::getPassword => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getName.'.required' => '请输入用户名',
            self::getName.'.max' => '用户名不能超过 255 个字符',
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getEmail.'.unique' => '该邮箱已被注册',
            self::getPassword.'.required' => '请输入密码',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
        ];
    }
}
```

- [ ] **Step 4: 编写 RegisterController**

创建 `app/Modules/Portal/Controllers/RegisterController.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Models\User;
use App\Modules\Portal\Requests\RegisterRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class RegisterController extends BaseController implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
            new Middleware('throttle:auth', only: ['store']),
        ];
    }

    #[OA\Get(path: '/register', summary: '注册页面', tags: ['模块'])]
    public function show(): Renderable
    {
        return view('portal::register');
    }

    #[OA\Post(path: '/register', summary: '提交注册', tags: ['模块'])]
    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create($request->validated());

        Auth::attempt($request->only(RegisterRequest::getEmail, RegisterRequest::getPassword));
        $request->session()->regenerate();

        return redirect('/');
    }
}
```

- [ ] **Step 5: 编写注册视图**

创建 `app/Modules/Portal/Views/register.blade.php`：

```blade
@extends('portal::layout')

@section('title', '注册 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">注册账号</h1>
                <form method="POST" action="/register">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">用户名</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">密码</label>
                        <div class="input-group" id="password-toggle">
                            <input type="password" id="password" name="password" ref="passwordInput"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" ref="toggleBtn" @click="togglePassword"
                                    class="btn btn-outline-secondary">显示</button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">确认密码</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">注册</button>
                </form>

                <p class="text-center small mt-3 mb-0">
                    已有账号？<a href="{{ route('login') }}">直接登录</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const passwordInput = ref(null);
                const toggleBtn = ref(null);
                const togglePassword = () => {
                    const input = passwordInput.value;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    toggleBtn.value.textContent = input.type === 'password' ? '显示' : '隐藏';
                };
                return { passwordInput, toggleBtn, togglePassword };
            },
        }).mount('#password-toggle');
    </script>
@endsection
```

- [ ] **Step 6: 重新生成路由并核对**

```bash
php artisan gen:route
git diff app/Modules/Portal/Routes/route.gen.php
```

Expected: 新增 `Route::get('register', ...)->name('register')` 与 `Route::post('register', ...)`；其他模块无变化

- [ ] **Step 7: 登录页链接改回命名路由**

`app/Modules/Portal/Views/login.blade.php` 中找到（Task 2 的临时字面量）：

```blade
<a href="/register" class="small">注册账号</a>
```

确认已经是 `<a href="{{ route('register') }}">注册账号</a>`（若 Task 2 用了字面量则改回命名路由）。

- [ ] **Step 8: 运行测试确认通过**

Run: `php artisan test --compact --filter=RegistrationTest`
Expected: PASS（4 个）
Run: `php artisan test --compact`
Expected: 全部 PASS

- [ ] **Step 9: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add app/Modules/Portal/Requests/RegisterRequest.php app/Modules/Portal/Controllers/RegisterController.php app/Modules/Portal/Views/register.blade.php app/Modules/Portal/Views/login.blade.php app/Modules/Portal/Routes/route.gen.php tests/Feature/Auth/RegistrationTest.php
git commit -m "feat(portal): 用户注册"
```

---

### Task 5: 忘记密码（发送重置邮件）

**Files:**
- Create: `app/Modules/Portal/Requests/ForgotPasswordRequest.php`
- Create: `app/Modules/Portal/Controllers/PasswordResetController.php`（本任务实现 `request`/`email`，`edit`/`update` 由 Task 6 追加）

- Create: `app/Modules/Portal/Views/forgot-password.blade.php`
- Modify: `app/Modules/Portal/Routes/route.gen.php`（gen:route 自动）
- Modify: `app/Modules/Portal/Views/login.blade.php`（「忘记密码」链接改回命名路由）
- Test: `tests/Feature/Auth/PasswordResetTest.php`（前三个测试）

**Interfaces:**
- Consumes: 布局契约（Task 2）、限流器 `auth`（Task 1）、`ResetPassword::createUrlUsing`（Task 1）、`password_reset_tokens` 表（默认迁移已有）
- Produces: 路由 `GET /forgot-password`（名 `forgot-password`）、`POST /forgot-password`
- Produces: `PasswordResetController` 骨架（HasMiddleware + `STATUS_MESSAGES` 常量 + `statusMessage()` 私有方法）——Task 6 复用
- Produces: 成功提示文案「重置链接已发送，请查收邮件」（session `status`）；失败「未找到该邮箱对应的用户」（`withErrors(['email' => …])`）

- [ ] **Step 1: 创建测试文件并写前三个失败测试**

```bash
php artisan make:test --pest --no-interaction Auth/PasswordResetTest
```

写入 `tests/Feature/Auth/PasswordResetTest.php`：

```php
<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('忘记密码页面可以正常显示', function () {
    $this->get('/forgot-password')->assertOk()->assertSee('忘记密码');
});

test('有效邮箱发送密码重置邮件', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
    $response->assertSessionHas('status', '重置链接已发送，请查收邮件');
});

test('未注册邮箱返回错误', function () {
    $response = $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody@example.com']);

    $response->assertSessionHasErrors(['email' => '未找到该邮箱对应的用户']);
});
```

- [ ] **Step 2: 运行测试确认失败**

Run: `php artisan test --compact --filter=PasswordResetTest`
Expected: FAIL——`/forgot-password` 路由不存在

- [ ] **Step 3: 编写 ForgotPasswordRequest**

创建 `app/Modules/Portal/Requests/ForgotPasswordRequest.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public const string getEmail = 'email';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['required', 'email'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
        ];
    }
}
```

- [ ] **Step 4: 编写 PasswordResetController（request/email）**

创建 `app/Modules/Portal/Controllers/PasswordResetController.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Modules\Portal\Requests\ForgotPasswordRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

class PasswordResetController extends BaseController implements HasMiddleware
{
    /**
     * 密码重置流程各状态的中文提示
     *
     * @var array<string, string>
     */
    private const array STATUS_MESSAGES = [
        Password::RESET_LINK_SENT => '重置链接已发送，请查收邮件',
        Password::RESET_THROTTLED => '请求过于频繁，请稍后再试',
        Password::INVALID_USER => '未找到该邮箱对应的用户',
        Password::INVALID_TOKEN => '重置链接无效或已过期',
        Password::PASSWORD_RESET => '密码重置成功，请使用新密码登录',
    ];

    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
            new Middleware('throttle:auth', only: ['email', 'update']),
        ];
    }

    #[OA\Get(path: '/forgot-password', summary: '忘记密码页面', tags: ['模块'])]
    public function request(): Renderable
    {
        return view('portal::forgot-password');
    }

    #[OA\Post(path: '/forgot-password', summary: '发送密码重置邮件', tags: ['模块'])]
    public function email(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->validated());

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withInput($request->only(ForgotPasswordRequest::getEmail))
                ->withErrors(['email' => $this->statusMessage($status)]);
        }

        return back()->with('status', $this->statusMessage($status));
    }

    private function statusMessage(string $status): string
    {
        return self::STATUS_MESSAGES[$status] ?? '操作失败，请重试';
    }
}
```

注意：`middleware()` 中 `only: ['email', 'update']` 的 `update` 方法 Task 6 才加——中间件 only 引用不存在的方法不报错，仅不命中，无需调整。

- [ ] **Step 5: 编写忘记密码视图**

创建 `app/Modules/Portal/Views/forgot-password.blade.php`：

```blade
@extends('portal::layout')

@section('title', '忘记密码 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-3 text-center">忘记密码</h1>
                <p class="text-muted small">输入注册邮箱，我们将向你发送密码重置链接。</p>
                <form method="POST" action="/forgot-password">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">发送重置链接</button>
                </form>

                <p class="text-center small mt-3 mb-0">
                    想起来了？<a href="{{ route('login') }}">直接登录</a>
                </p>
            </div>
        </div>
    </div>
@endsection
```

- [ ] **Step 6: 重新生成路由并核对**

```bash
php artisan gen:route
git diff app/Modules/Portal/Routes/route.gen.php
```

Expected: 新增 `Route::get('forgot-password', ...)->name('forgot-password')` 与 `Route::post('forgot-password', ...)`；其他模块无变化

- [ ] **Step 7: 登录页「忘记密码」链接改回命名路由**

`app/Modules/Portal/Views/login.blade.php` 中把 Task 2 的临时字面量 `<a href="/forgot-password" class="small">忘记密码？</a>` 改为 `<a href="{{ route('forgot-password') }}" class="small">忘记密码？</a>`。

- [ ] **Step 8: 运行测试确认通过**

Run: `php artisan test --compact --filter=PasswordResetTest`
Expected: PASS（3 个）
Run: `php artisan test --compact`
Expected: 全部 PASS

- [ ] **Step 9: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add app/Modules/Portal/Requests/ForgotPasswordRequest.php app/Modules/Portal/Controllers/PasswordResetController.php app/Modules/Portal/Views/forgot-password.blade.php app/Modules/Portal/Views/login.blade.php app/Modules/Portal/Routes/route.gen.php tests/Feature/Auth/PasswordResetTest.php
git commit -m "feat(portal): 忘记密码"
```

---

### Task 6: 重置密码

**Files:**
- Create: `app/Modules/Portal/Requests/ResetPasswordRequest.php`
- Modify: `app/Modules/Portal/Controllers/PasswordResetController.php`（追加 `edit`/`update`）
- Create: `app/Modules/Portal/Views/reset-password.blade.php`
- Modify: `app/Modules/Portal/Routes/route.gen.php`（gen:route 自动）
- Test: `tests/Feature/Auth/PasswordResetTest.php`（追加三个测试）

**Interfaces:**
- Consumes: `STATUS_MESSAGES`/`statusMessage()`（Task 5）、`ResetPassword::createUrlUsing` 生成的 `?token=…&email=…` 查询串格式（Task 1）
- Produces: 路由 `GET /reset-password`（名 `reset-password`）、`POST /reset-password`
- Produces: 重置成功 → 跳 `/login` 带 session `status`「密码重置成功，请使用新密码登录」；失败 → 回跳 `withErrors(['email' => …])`

- [ ] **Step 1: 追加失败测试**

在 `tests/Feature/Auth/PasswordResetTest.php` 顶部 use 区追加：

```php
use Illuminate\Support\Facades\Password as PasswordBroker;
```

文件末尾追加：

```php
test('重置密码页面可以正常显示', function () {
    $this->get('/reset-password?token=abc')->assertOk()->assertSee('重置密码');
});

test('用户可以使用有效令牌重置密码', function () {
    $user = User::factory()->create();
    $token = PasswordBroker::createToken($user);

    $response = $this->from('/reset-password')->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHas('status', '密码重置成功，请使用新密码登录');

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'new-password123',
    ]);
    $this->assertAuthenticated();
});

test('无效令牌不能重置密码', function () {
    $user = User::factory()->create();

    $response = $this->from('/reset-password')->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertSessionHasErrors(['email' => '重置链接无效或已过期']);
});
```

（注：`edit` 页面测试用查询串 `?token=abc`，与 Task 1 覆盖的链接格式一致。）

- [ ] **Step 2: 运行测试确认失败**

Run: `php artisan test --compact --filter=PasswordResetTest`
Expected: 新增 3 个 FAIL——`/reset-password` 路由不存在

- [ ] **Step 3: 编写 ResetPasswordRequest**

创建 `app/Modules/Portal/Requests/ResetPasswordRequest.php`：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public const string getToken = 'token';

    public const string getEmail = 'email';

    public const string getPassword = 'password';

    public const string getPasswordConfirmation = 'password_confirmation';

    /**
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            self::getToken => ['required', 'string'],
            self::getEmail => ['required', 'email'],
            self::getPassword => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getToken.'.required' => '重置令牌缺失',
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getPassword.'.required' => '请输入密码',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
        ];
    }
}
```

- [ ] **Step 4: 追加 edit/update 方法**

`app/Modules/Portal/Controllers/PasswordResetController.php` 中：

use 区追加：

```php
use App\Models\User;
use App\Modules\Portal\Requests\ResetPasswordRequest;
```

在 `email()` 方法之后、`statusMessage()` 之前插入：

```php
    #[OA\Get(path: '/reset-password', summary: '重置密码页面', tags: ['模块'])]
    public function edit(): Renderable
    {
        return view('portal::reset-password');
    }

    #[OA\Post(path: '/reset-password', summary: '提交重置密码', tags: ['模块'])]
    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only(
                ResetPasswordRequest::getEmail,
                ResetPasswordRequest::getPassword,
                ResetPasswordRequest::getPasswordConfirmation,
                ResetPasswordRequest::getToken,
            ),
            function (User $user, string $password): void {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only(ResetPasswordRequest::getEmail))
                ->withErrors(['email' => $this->statusMessage($status)]);
        }

        return redirect('/login')->with('status', $this->statusMessage($status));
    }
```

- [ ] **Step 5: 编写重置密码视图**

创建 `app/Modules/Portal/Views/reset-password.blade.php`：

```blade
@extends('portal::layout')

@section('title', '重置密码 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">重置密码</h1>
                <form method="POST" action="/reset-password">
                    @csrf

                    <input type="hidden" name="token" value="{{ request('token') }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">新密码</label>
                        <div class="input-group" id="password-toggle">
                            <input type="password" id="password" name="password" ref="passwordInput"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" ref="toggleBtn" @click="togglePassword"
                                    class="btn btn-outline-secondary">显示</button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">确认新密码</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">重置密码</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const passwordInput = ref(null);
                const toggleBtn = ref(null);
                const togglePassword = () => {
                    const input = passwordInput.value;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    toggleBtn.value.textContent = input.type === 'password' ? '显示' : '隐藏';
                };
                return { passwordInput, toggleBtn, togglePassword };
            },
        }).mount('#password-toggle');
    </script>
@endsection
```

- [ ] **Step 6: 重新生成路由并核对**

```bash
php artisan gen:route
git diff app/Modules/Portal/Routes/route.gen.php
```

Expected: 新增 `Route::get('reset-password', ...)->name('reset-password')` 与 `Route::post('reset-password', ...)`；其他模块无变化

- [ ] **Step 7: 运行测试确认通过**

Run: `php artisan test --compact --filter=PasswordResetTest`
Expected: PASS（6 个）
Run: `php artisan test --compact`
Expected: 全部 PASS

- [ ] **Step 8: 格式化并提交**

```bash
vendor/bin/pint --dirty --format agent
git add app/Modules/Portal/Requests/ResetPasswordRequest.php app/Modules/Portal/Controllers/PasswordResetController.php app/Modules/Portal/Views/reset-password.blade.php app/Modules/Portal/Routes/route.gen.php tests/Feature/Auth/PasswordResetTest.php
git commit -m "feat(portal): 重置密码"
```

---

### Task 7: 收尾验证

**Files:**
- 无新文件；如有修正，修正对应任务引入的文件

**Interfaces:**
- Consumes: 全部前序任务成果

- [ ] **Step 1: 全量测试**

Run: `php artisan test --compact`
Expected: 全部 PASS（Example 2 + Authentication 6 + RateLimit 1 + Registration 4 + PasswordReset 6 = 19 个）

- [ ] **Step 2: 代码风格**

Run: `vendor/bin/pint --dirty --format agent`
Expected: 无待修复项

- [ ] **Step 3: gen:route 幂等性检查**

```bash
php artisan gen:route
git status --short app/Modules app/Api
```

Expected: 所有 route.gen.php 无变更（再生成不产生 diff）

- [ ] **Step 4: 路由清单核对**

Run: `php artisan route:list --except-vendor`
Expected: 出现 9 条新路由（login×2、logout、register×2、forgot-password×2、reset-password×2）

- [ ] **Step 5: 手动冒烟（开发者执行）**

提示用户执行（或由用户确认已完成）：`composer run dev` 启动后访问 `http://127.0.0.1:8000`：

1. `/register` 注册新账号 → 自动登录跳首页
2. `/logout`（需临时以 POST 触发，如开发者工具或路由页）→ 回登录页
3. `/login` 登录（勾选记住我）→ 首页
4. `/forgot-password` 提交邮箱 → 页面提示已发送；`storage/logs/laravel.log` 中查收 `MAIL_MAILER=log` 记录的重置链接（`/reset-password?token=…&email=…`）
5. 打开重置链接 → 设置新密码 → 提示成功 → 用新密码登录

- [ ] **Step 6: 如有修正则提交**

```bash
git add -A
git commit -m "fix: 认证模块收尾修正"
```

（无修正则跳过本步）

---

## Self-Review 记录

- **Spec 覆盖**：spec §3 路由表 9 条 ↔ Task 3/4/5/6 各自生成；§4 校验规则 ↔ 4 个 Request；§4.2-4.4 控制器行为 ↔ Task 3/4/5/6；§5 视图 ↔ Task 2/4/5/6；§6 测试矩阵 ↔ 四个测试文件（spec 中「登录成功跳回原意图页」因当前无 GET 型受保护路由，intended 路径无法构造，以 `assertRedirect('/')` 等价覆盖，已在计划中注明）；§2 全部决策在 Global Constraints 落实
- **占位符扫描**：无 TBD/TODO；所有代码步骤含完整代码；Task 2 的临时字面量链接有明确的后续替换步骤（Task 4 Step 7、Task 5 Step 7）
- **类型一致性**：`LoginRequest::authenticate(): void`、`statusMessage(string $status): string`、中间件方法名（`logout`/`store`/`email`/`update`）在各任务间引用一致；`RegisterRequest::getEmail` 等常量跨控制器引用经 use 导入
