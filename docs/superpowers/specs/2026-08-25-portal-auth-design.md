# Portal 认证模块设计（登录 / 注册 / 忘记密码）

- 日期：2026-08-25
- 状态：已确认（分段评审通过）
- 范围：`app/Modules/Portal` 模块的完整 Session 认证流程

## 1. 背景与目标

HugeCMS 是 Laravel 13 模块化 CMS（`app/Modules/{Portal,Admin,User}` 网页模块 + `app/Api/*` JSON API 模块，路由由 `php artisan gen:route` 依据控制器 OA 属性生成）。目前 `GET /login` 已存在但仅为占位页。

本设计实现基于 **Laravel 原生 Session Guard** 的完整认证流程：

- 登录 / 登出（含「记住我」、登录限流、意图跳转）
- 开放注册（注册成功自动登录，**不做邮箱验证**）
- 忘记密码 / 重置密码（基于 `password_reset_tokens` 表 + 邮件重置链接）

明确不做：邮箱验证、2FA、确认密码、邀请制注册、社交登录、API token 认证（Sanctum/JWT）。

## 2. 技术决策记录

| 决策点 | 结论 | 理由 |
|---|---|---|
| 认证方式 | Session 表单登录（非 AJAX API） | 零新依赖，契合 Blade + Bootstrap 现有管线 |
| 实现方式 | 手写原生 Laravel Auth（非 Fortify/Breeze） | 保持模块化路由与静态资产约定一致性 |
| 功能范围 | 完整集：记住我 + 限流 + intended 跳转 + 忘记密码 + 开放注册 | 用户选定 |
| 注册策略 | 开放注册，自动登录 | 用户选定 |
| 邮箱验证 | 不做（`User` 不实现 `MustVerifyEmail`） | 用户选定；未来增强不影响本次结构 |
| 认证中间件 | 内置 `auth` | `app/Http/Middleware/Auth.php` 为 RBAC 授权预留，本次不动 |
| 登录限流 | 控制器内手动 `RateLimiter`（5 次/分钟，按 email+IP） | 只计失败次数，成功登录不消耗额度 |
| 注册/忘记密码限流 | `throttle:auth` 中间件 | 无「部分成功」语义，中间件即可 |
| 邮件驱动 | `MAIL_MAILER=log`（现状），测试用 Notification fake | 开发期不外发 |

## 3. 架构与路由

### 3.1 文件结构

```
app/Modules/Portal/
├── Controllers/
│   ├── AuthController.php           # 重写：login / authenticate / logout
│   ├── RegisterController.php       # 新增：show / store
│   ├── PasswordResetController.php  # 新增：request / email / edit / update
│   └── BaseController.php           # 现有不动
├── Requests/                        # 新增目录（沿用 API 侧 FormRequest 约定）
│   ├── LoginRequest.php
│   ├── RegisterRequest.php
│   ├── ForgotPasswordRequest.php
│   └── ResetPasswordRequest.php
└── Views/
    ├── layout.blade.php             # 增强为共享布局（Bootstrap 资产 + Flash 区）
    ├── login.blade.php              # 重写占位页
    ├── register.blade.php           # 新增
    ├── forgot-password.blade.php    # 新增
    └── reset-password.blade.php     # 新增
```

### 3.2 路由表

由控制器 OA 属性 + `php artisan gen:route` 生成至 `Routes/route.gen.php`（不改手写文件）：

| 方法 | 路径 | 控制器@方法 | 命名路由 | 中间件 |
|---|---|---|---|---|
| GET | /login | AuthController@login | `login` | guest |
| POST | /login | AuthController@authenticate | — | guest |
| POST | /logout | AuthController@logout | — | auth |
| GET | /register | RegisterController@show | `register` | guest |
| POST | /register | RegisterController@store | — | guest + throttle:auth |
| GET | /forgot-password | PasswordResetController@request | `forgot-password` | guest |
| POST | /forgot-password | PasswordResetController@email | — | guest + throttle:auth |
| GET | /reset-password | PasswordResetController@edit | `reset-password` | guest |
| POST | /reset-password | PasswordResetController@update | — | guest + throttle:auth |

**中间件配置**（`bootstrap/app.php`）：
- `$middleware->redirectGuestsTo(fn () => route('login'))`
- `$middleware->redirectUsersTo('/')`

- `ResetPassword::createUrlUsing()`（AppServiceProvider）：因路由名非 `password.reset`，须覆盖默认重置链接为 `/reset-password?token=…&email=…`
- 登录跳转 `redirect()->intended('/')`：`index` 路由名被 Admin/Portal/User 三模块重复注册，不可用 `route('index')`

**限流器定义**（`AppServiceProvider::boot()`）：
- `RateLimiter::for('login', ...)`：键为 `email|IP`，5 次/分钟，仅供 `AuthController@authenticate` 手动调用（重置 token 经查询串 `?token=…&email=…` 传入）
- `RateLimiter::for('auth', ...)`：按 IP，5 次/分钟，供注册/忘记密码路由的 `throttle:auth` 中间件使用

## 4. 控制器与校验

### 4.1 FormRequest（字段常量 + 中文 messages，沿用 API 侧约定，不加 OA Schema）

| Request | 规则 |
|---|---|
| LoginRequest | email: `required\|email`；password: `required\|string` |
| RegisterRequest | name: `required\|string\|max:255`；email: `required\|email\|max:255\|unique:users,email`；password: `required\|string\|confirmed\|Password::defaults()`；password_confirmation: `required` |
| ForgotPasswordRequest | email: `required\|email` |
| ResetPasswordRequest | token: `required`；email: `required\|email`；password: `required\|string\|confirmed\|Password::defaults()`；password_confirmation: `required` |

### 4.2 AuthController

```
login():        返回 portal::login 视图
authenticate(): ① 手动 RateLimiter（`login` 限流器）：tooManyAttempts → back + 「尝试过多，请 N 秒后重试」（含 retryAfter）
                ② Auth::attempt(credentials, remember) 失败 → hit() 限流器 → back withInput(除 password) + 「邮箱或密码错误」
                ③ 成功 → clear() + session()->regenerate() → redirect()->intended(route('index'))
logout():       Auth::logout() + session()->invalidate() + regenerateToken() → redirect(route('login'))
```

### 4.3 RegisterController

`User::create()`（密码由模型 `hashed` cast 自动哈希）→ `Auth::attempt()` → `regenerate()` → 跳 `index`。

### 4.4 PasswordResetController

- `email()`：`Password::sendResetLink()`，成功/失败均以 session `status`/`error` 回跳 forgot-password（不泄露邮箱是否注册）
- `edit()`：渲染 reset-password 视图，携带 token
- `update()`：`Password::reset()` 成功 → 跳 login + 「重置成功，请登录」status

## 5. 视图设计

**共享布局**：现有静态资产（bootstrap.min.css / jquery / vue.global.prod.js / bootstrap.min.js）+ `@yield('content')` + `@yield('scripts')` + Flash 提示区（`session('status')` 绿色 alert / `session('error')` 红色 alert）。

**表单页统一骨架**：全屏居中（`min-vh-100`）→ 卡片（约 380px，圆角阴影）→ 表单（命名路由 action + `@csrf`）。字段错误渲染 `is-invalid` + `invalid-feedback`。

- **login**：email + password + remember checkbox + 「忘记密码？」链接 + 「注册账号」链接
- **register**：name + email + password + confirm
- **forgot-password**：email
- **reset-password**：email + 隐藏 token + password + confirm

**JS 策略**：无 JS 完全可用；Vue 仅做渐进增强（密码显示切换等），不 SPA 化。

**错误文案**：登录失败统一「邮箱或密码错误」（模糊提示，不泄露字段）；其余字段错误由 FormRequest 中文 messages 提供。

## 6. 测试策略（Pest 5）

`tests/Pest.php` 打开被注释的 `->use(RefreshDatabase::class)`（现有预留开关）。

| 文件（tests/Feature/Auth/） | 覆盖点 |
|---|---|
| AuthenticationTest | 登录页可访问；正确凭据登录成功；错误凭据回跳+提示；未登录访问受保护页跳 login（intended）；登出失效 |
| RegistrationTest | 注册页可访问；有效数据创建+自动登录；重复邮箱被拒；弱密码被拒 |
| PasswordResetTest | 忘记密码页可访问；有效邮箱发链接（Notification::fake）；无效邮箱回错误；重置后可登录；坏 token 失败 |
| LoginRateLimitTest | 5 次失败后第 6 次被限流（提示含等待时间） |

造数用 `User::factory()`；邮件用 `Notification::fake()` + `assertSent`（Laravel 13 重置邮件走 notification 通道，实现时按实际 API 校准）。

## 7. 验收标准

1. 全部测试通过（`php artisan test --compact`）
2. `vendor/bin/pint --dirty` 通过
3. 手动流程：注册 → 登出 → 登录（含记住我）→ 登出 → 忘记密码 → 收到重置邮件（log 驱动查看）→ 重置 → 新密码登录
4. 未登录访问受保护页跳 `/login`，登录后回跳原页
