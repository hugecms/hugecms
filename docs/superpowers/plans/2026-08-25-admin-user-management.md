# Admin 用户管理模块实现计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 实现后台用户管理：status 字段与禁用登录、列表四区页面（筛选/表格/分页）、新建/编辑独立页、confirm+批量删除、菜单点亮。

**Architecture:** 服务端渲染 Blade（GET 查询串筛选分页）；查询走 `UserService->page()`（LIKE 条件）；写操作 FormRequest 校验 + `User::create/update`；删除单端点收 id/ids[]；复用 antd 风格令牌（hugecms-admin.css）与 icons 局部视图。

**Tech Stack:** Laravel 13（int enum cast、迁移、分页）、Blade、Bootstrap 5、Pest 5。

**Spec:** `docs/superpowers/specs/2026-08-25-admin-user-management-design.md`

## Global Constraints

- 本模块不碰 `app/Api/Admin/*`（API 层保持原样）
- 新控制器在 `App\Modules\Admin\Controllers`（与 API 层 UserController 不同命名空间，gen:route 会为两个同名类分别生成——执行时核对 Admin 模块 route.gen.php 只含模块内控制器）
- 表单方法伪造：编辑用 `@method('PUT')`，删除用 `@method('DELETE')`
- `UserService->page()` 返回结构以 CurdRepository::page 实际返回为准（实现时先 tinker 验证一次再写视图）
- 每任务：`php artisan test --compact` + `vendor/bin/pint --dirty --format agent`
- 本会话不执行 git commit（用户指示）
- 决策提醒：菜单「用户管理」route 修正在 status 迁移同文件内完成

## 文件结构总览

```
创建：
  database/migrations/2026_08_25_150000_add_status_to_users_table.php
  app/Modules/Admin/Controllers/UserController.php
  app/Modules/Admin/Requests/UserIndexRequest.php
  app/Modules/Admin/Requests/UserStoreRequest.php
  app/Modules/Admin/Requests/UserUpdateRequest.php
  app/Modules/Admin/Views/user/index.blade.php
  app/Modules/Admin/Views/user/create.blade.php
  app/Modules/Admin/Views/user/edit.blade.php
  tests/Feature/Admin/UserManagementTest.php
修改：
  app/Enums/UserStatusEnum.php（枚举 + label）
  app/Models/User.php（fillable/casts/isEnabled）
  database/factories/UserFactory.php（status 默认 + disabled state）
  app/Modules/Portal/Requests/LoginRequest.php（禁用拦截）
  database/seeders/MenuSeeder.php（用户管理 route 点亮）
  tests/Feature/Auth/AuthenticationTest.php（禁用登录 1 例）——并入 UserManagementTest 也可，放这里语义更近
```

---

### Task 1: status 字段 + 枚举 + 禁用登录

**Files:**
- Create: `database/migrations/2026_08_25_150000_add_status_to_users_table.php`
- Modify: `app/Enums/UserStatusEnum.php`、`app/Models/User.php`、`database/factories/UserFactory.php`、`app/Modules/Portal/Requests/LoginRequest.php`
- Test: `tests/Feature/Auth/AuthenticationTest.php`（+1 例）

**Interfaces:**
- Produces: `users.status`（tinyint 1/0）、`UserStatusEnum::{Enabled,Disabled}`（int backed + `label()`）、`User::isEnabled(): bool`、factory `disabled()` state——后续任务与测试依赖
- Produces: 菜单「用户管理」route=`admin.user.index`（迁移内 update，本任务执行后存量库即点亮数据，但路由 Task 3 才有——中间态点击 404 属预期，侧栏渲染不受影响）

- [ ] **Step 1: 写失败测试（AuthenticationTest 追加）**

```php
test('禁用用户不能登录', function () {
    $user = User::factory()->disabled()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect('/login')
        ->assertSessionHasErrors('email');
});
```

- [ ] **Step 2: 跑测试确认失败**

Run: `php artisan test --compact --filter=禁用用户不能登录`
Expected: FAIL——factory 无 disabled 方法（Error）

- [ ] **Step 3: 迁移 + 枚举 + 模型 + 工厂**

迁移（含菜单修正与回填）：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment('状态：1 启用 0 禁用')->after('remember_token');
        });

        DB::table('users')->update(['status' => 1]);

        DB::table('menus')->where('name', '用户管理')->where('route', '')->update(['route' => 'admin.user.index']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        DB::table('menus')->where('name', '用户管理')->where('route', 'admin.user.index')->update(['route' => '']);
    }
};
```

命令：`php artisan make:migration add_status_to_users_table --no-interaction` 后写入上述内容。

枚举：

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatusEnum: int
{
    case Enabled = 1;

    case Disabled = 0;

    public function label(): string
    {
        return $this === self::Enabled ? '启用' : '禁用';
    }
}
```

模型 User——`#[Fillable(['name', 'email', 'password'])]` 改为 `#[Fillable(['name', 'email', 'password', 'status'])]`；casts() 追加：

```php
'status' => UserStatusEnum::class,
```

并加方法（casts 上方）：

```php
    /**
     * 用户是否启用
     */
    public function isEnabled(): bool
    {
        return $this->status === UserStatusEnum::Enabled;
    }
```

工厂 definition 追加：

```php
'status' => UserStatusEnum::Enabled->value,
```

并加 state 方法：

```php
    /**
     * 禁用状态的用户
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatusEnum::Disabled->value,
        ]);
    }
```

（use `App\Enums\UserStatusEnum`）

- [ ] **Step 4: LoginRequest 禁用拦截**

`authenticate()` 中 `Auth::attempt(...)` 成功分支（`RateLimiter::clear` 之前）插入：

```php
        $user = Auth::user();
        if ($user instanceof \App\Models\User && ! $user->isEnabled()) {
            Auth::logout();

            throw ValidationException::withMessages([
                self::getEmail => '账号已被禁用，请联系管理员',
            ]);
        }
```

（use 导入 `App\Models\User` 后类型直接写 `User`）

- [ ] **Step 5: 跑测试确认通过 + 全量回归**

Run: `php artisan test --compact`
Expected: 29/29 PASS

- [ ] **Step 6: pint**

```bash
vendor/bin/pint --dirty --format agent
```

---

### Task 2: 索引页（筛选/列表/分页）

**Files:**
- Create: `app/Modules/Admin/Controllers/UserController.php`（本任务实现 index，其余方法占位由后续任务补——**OA 属性本任务只写 index/create/store/edit/update/destroy 全量**，避免多次 gen:route 中间态；未实现方法体先 `abort(404)` 由 Task 3/4 替换）

  > 修正：一次写全 OA 会让 route.gen.php 立即出现指向 404 页的「幽灵路由」，中间提交不自洽。改为：Task 2 只写 index 的 OA+实现；Task 3 补 create/store；Task 4 补 edit/update/destroy，每任务跑一次 gen:route。

- Create: `app/Modules/Admin/Requests/UserIndexRequest.php`
- Create: `app/Modules/Admin/Views/user/index.blade.php`
- Test: `tests/Feature/Admin/UserManagementTest.php`（前 4 例）

**Interfaces:**
- Consumes: `UserService->page(array $condition, int $page, int $perPage): array`（返回结构实现前先 tinker 验证：`['data'=>[], 'total'=>N, ...]` 形态按实际调整视图）
- Produces: `GET /admin/user`（名 admin.user.index）；视图契约 `users/filters/statusOptions/pageSize` 变量；列表行含复选框 name="ids[]"

- [ ] **Step 1: tinker 验证 page 返回结构**

```bash
php artisan tinker --execute '$r = app(App\Services\UserService::class)->page([], 1, 10); echo json_encode(array_keys($r), JSON_UNESCAPED_UNICODE); print_r($r);'
```

记下实际键名（data/total/currentPage/lastPage 等），后续视图按此渲染。

- [ ] **Step 2: 写失败测试（前 4 例）**

```php
<?php

use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create();
}

test('用户列表页可访问并渲染表格', function () {
    User::factory()->count(3)->create();

    $response = $this->actingAs(adminUser())->get('/admin/user');

    $response->assertOk()->assertSee('用户管理');
    expect($response->getContent())->toContain('<table');
});

test('用户名模糊筛选', function () {
    User::factory()->create(['name' => '张三丰']);
    User::factory()->create(['name' => '李四']);

    $response = $this->actingAs(adminUser())->get('/admin/user?name=张三');

    $response->assertOk()->assertSee('张三丰')->assertDontSee('李四');
});

test('邮箱精确筛选', function () {
    User::factory()->create(['email' => 'target@example.com']);
    User::factory()->create(['email' => 'other@example.com']);

    $response = $this->actingAs(adminUser())->get('/admin/user?email=target@example.com');

    $response->assertOk()->assertSee('target@example.com')->assertDontSee('other@example.com');
});

test('状态筛选', function () {
    User::factory()->create(['name' => '启用人']);
    User::factory()->disabled()->create(['name' => '禁用人']);

    $response = $this->actingAs(adminUser())->get('/admin/user?status=0');

    $response->assertOk()->assertSee('禁用人')->assertDontSee('启用人');
});
```

- [ ] **Step 3: 跑测试确认失败（404）**

- [ ] **Step 4: UserIndexRequest**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public const string getEmail = 'email';

    public const string getName = 'name';

    public const string getStatus = 'status';

    public const string getPage = 'page';

    public const string getPageSize = 'pageSize';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['nullable', 'email'],
            self::getName => ['nullable', 'string', 'max:50'],
            self::getStatus => ['nullable', 'integer', 'in:0,1'],
            self::getPage => ['nullable', 'integer', 'min:1'],
            self::getPageSize => ['nullable', 'integer', 'in:10,20,50'],
        ];
    }
}
```

- [ ] **Step 5: UserController@index + gen:route**

控制器（本任务完整文件，仅 index 有 OA）：

```php
<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Enums\UserStatusEnum;
use App\Modules\Admin\Requests\UserIndexRequest;
use App\Services\UserService;
use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[OA\Get(path: '/user', summary: '用户列表页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function index(UserIndexRequest $request): Renderable
    {
        $filters = array_filter($request->only([
            UserIndexRequest::getEmail,
            UserIndexRequest::getName,
            UserIndexRequest::getStatus,
        ]), fn ($value) => $value !== null && $value !== '');

        $condition = [];
        if (isset($filters[UserIndexRequest::getEmail])) {
            $condition[] = ['email', '=', $filters[UserIndexRequest::getEmail]];
        }
        if (isset($filters[UserIndexRequest::getName])) {
            $condition[] = ['name', 'like', '%'.$filters[UserIndexRequest::getName].'%'];
        }
        if (isset($filters[UserIndexRequest::getStatus])) {
            $condition[] = ['status', '=', (int) $filters[UserIndexRequest::getStatus]];
        }

        $page = (int) $request->query(UserIndexRequest::getPage, '1');
        $pageSize = (int) $request->query(UserIndexRequest::getPageSize, '10');

        $result = $this->userService->page($condition, $page, $pageSize);

        return view('admin::user.index', [
            'users' => $result, // 按 Step 1 实际结构调整：分页器实例或数组
            'filters' => $filters,
            'statusOptions' => [
                ['value' => UserStatusEnum::Enabled->value, 'label' => UserStatusEnum::Enabled->label()],
                ['value' => UserStatusEnum::Disabled->value, 'label' => UserStatusEnum::Disabled->label()],
            ],
            'pageSize' => $pageSize,
        ]);
    }
}
```

> 注：`page()` 返回数组还是 LengthAwarePaginator 以 Step 1 为准。若返回数组（UserQueryResponse 的来源形态），视图分页区用 `$result['total']`/`lastPage` 手写分页链接（查询串拼接 page=N），`links()` 仅在是 Paginator 实例时用。CurdRepository::page 的返回是 `['data'=>..., 'total'=>...]` 数组（UserService 无覆盖）——按数组实现，分页链接手写。

```bash
php artisan gen:route
vendor/bin/pint app/Modules/Admin/Routes/route.gen.php --format agent
php artisan route:list --path=admin/user
```

Expected: `GET admin/user` 出现

- [ ] **Step 6: index.blade.php（四区）**

按 Step 1 的返回结构渲染；骨架（分页链接助手见文末附录）：

```blade
@extends('admin::layout')

@section('title', '用户管理 - 管理后台')
@section('page-title', '用户管理')

@section('content')
    {{-- 标题区 --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-1">用户管理</h1>
            <p class="text-muted small mb-0">管理系统注册用户</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">+ 新建用户</a>
    </div>

    {{-- 筛选区 --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.user.index') }}" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label mb-1">邮箱</label>
                    <input type="email" name="email" value="{{ $filters['email'] ?? '' }}" class="form-control form-control-sm" placeholder="精确匹配">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1">用户名</label>
                    <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control form-control-sm" placeholder="模糊匹配">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1">状态</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">全部</option>
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" {{ (string) ($filters['status'] ?? '') === (string) $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">查询</button>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm">重置</a>
                </div>
            </form>
        </div>
    </div>

    {{-- 列表区 --}}
    <div class="card">
        <div class="card-body">
            <form id="batch-form" method="POST" action="{{ route('admin.user.index') }}" onsubmit="return confirmBatch()">
                @csrf
                @method('DELETE')
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">共 {{ $users['total'] ?? 0 }} 条</span>
                    <button type="submit" id="batch-btn" class="btn btn-danger btn-sm d-none" disabled>批量删除 (<span id="batch-count">0</span>)</button>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 36px;"><input type="checkbox" id="check-all"></th>
                                <th>ID</th><th>用户名</th><th>邮箱</th><th>状态</th><th>注册时间</th><th class="text-end">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users['data'] ?? [] as $user)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $user['id'] }}" class="row-check"></td>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td><span class="badge rounded-pill {{ $user['status'] === 1 ? 'bg-success' : 'bg-secondary' }}">{{ $user['status'] === 1 ? '启用' : '禁用' }}</span></td>
                                <td>{{ $user['created_at'] }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.user.edit', $user['id']) }}" class="link-primary text-decoration-none me-2">编辑</a>
                                    {{-- 行删表单 Task 4 路由就绪后启用，本任务占位文本 --}}
                                    <span class="text-muted small">删除</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">暂无数据</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
            {{-- 分页区：手写分页链接（附录 helper）--}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // 全选联动与批量按钮显隐
    </script>
@endsection
```

> create/edit 路由 Task 3/4 才生成——本任务「新建用户」按钮与「编辑」链接的 `route()` 会抛异常。**临时**用 `/admin/user/create`、`/admin/user/edit/{$id}` 字面量，Task 3/4 各自替换。status 值比较注意 `$user['status']` 可能是 int 或字符串，用 `(int)` 收敛。

- [ ] **Step 7: 跑测试确认通过（4 例）+ 回归**

- [ ] **Step 8: pint**

---

### Task 3: 新建用户（create/store）

**Files:**
- Modify: `app/Modules/Admin/Controllers/UserController.php`（+create/store 与 OA）
- Create: `app/Modules/Admin/Requests/UserStoreRequest.php`
- Create: `app/Modules/Admin/Views/user/create.blade.php`
- Modify: `app/Modules/Admin/Views/user/index.blade.php`（新建按钮换命名路由）
- Test: `tests/Feature/Admin/UserManagementTest.php`（+2 例）

**Interfaces:**
- Produces: `GET /admin/user/create`（名 admin.user.create）、`POST /admin/user`
- Produces: 成功 redirect `admin.user.index` 带 session status「创建成功」

- [ ] **Step 1: 失败测试（+2）**

```php
test('新建用户成功', function () {
    $response = $this->actingAs(adminUser())->post('/admin/user', [
        'name' => '新用户',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'status' => '1',
    ]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '创建成功');
    $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'name' => '新用户']);
});

test('新建重复邮箱失败', function () {
    $exist = User::factory()->create();

    $response = $this->from('/admin/user/create')->post('/admin/user', [
        'name' => '重复者',
        'email' => $exist->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'status' => '1',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseMissing('users', ['name' => '重复者']);
});
```

- [ ] **Step 2: 确认失败**

- [ ] **Step 3: UserStoreRequest**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getPassword = 'password';

    public const string getStatus = 'status';

    /**
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:50'],
            self::getEmail => ['required', 'email', 'max:255', 'unique:users,email'],
            self::getPassword => ['required', 'string', 'confirmed', Password::defaults()],
            self::getStatus => ['required', 'integer', 'in:0,1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::getName.'.required' => '请输入用户名',
            self::getName.'.max' => '用户名不能超过 50 个字符',
            self::getEmail.'.required' => '请输入邮箱',
            self::getEmail.'.email' => '邮箱格式不正确',
            self::getEmail.'.unique' => '该邮箱已被注册',
            self::getPassword.'.required' => '请输入密码',
            self::getPassword.'.confirmed' => '两次输入的密码不一致',
            self::getStatus.'.required' => '请选择状态',
        ];
    }
}
```

- [ ] **Step 4: 控制器 +create/store + OA + gen:route**

```php
    #[OA\Get(path: '/user/create', summary: '新建用户页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function create(): Renderable
    {
        return view('admin::user.create', [
            'statusOptions' => [
                ['value' => UserStatusEnum::Enabled->value, 'label' => UserStatusEnum::Enabled->label()],
                ['value' => UserStatusEnum::Disabled->value, 'label' => UserStatusEnum::Disabled->label()],
            ],
        ]);
    }

    #[OA\Post(path: '/user', summary: '保存新建用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function store(UserStoreRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->string(UserStoreRequest::getName)->toString(),
            'email' => $request->string(UserStoreRequest::getEmail)->toString(),
            'password' => $request->string(UserStoreRequest::getPassword)->toString(),
            'status' => (int) $request->string(UserStoreRequest::getStatus)->toString(),
        ]);

        return redirect()->route('admin.user.index')->with('status', '创建成功');
    }
```

（use：`Illuminate\Http\RedirectResponse`、`App\Modules\Admin\Requests\UserStoreRequest`、`App\Models\User`）

```bash
php artisan gen:route && vendor/bin/pint app/Modules/Admin/Routes/route.gen.php --format agent
```

- [ ] **Step 5: create.blade.php**

表单卡片：name/email/password/password_confirmation/status(radio) + 取消按钮（回 index）+ 提交；错误回显 `@error` 惯例同 login/register。action `{{ route('admin.user.index') }}`（POST 同路径）。

- [ ] **Step 6: index 页新建按钮换回命名路由**

`/admin/user/create` 字面量 → `{{ route('admin.user.create') }}`

- [ ] **Step 7: 测试通过 + 回归 + pint**

---

### Task 4: 编辑与删除（edit/update/destroy + 行删/批量）

**Files:**
- Modify: `app/Modules/Admin/Controllers/UserController.php`（+edit/update/destroy 与 OA）
- Create: `app/Modules/Admin/Requests/UserUpdateRequest.php`
- Create: `app/Modules/Admin/Views/user/edit.blade.php`
- Modify: `app/Modules/Admin/Views/user/index.blade.php`（编辑链接换命名路由、行删表单替换占位文本、批量表单 action 换命名路由）
- Test: `tests/Feature/Admin/UserManagementTest.php`（+4 例）

**Interfaces:**
- Produces: `GET /admin/user/edit/{id}`（名 admin.user.edit）、`PUT /admin/user/edit/{id}`、`DELETE /admin/user`（名 admin.user.destroy，gen 派生）
- Produces: 更新成功 status「更新成功」；删除（单/批量）status「删除成功」

- [ ] **Step 1: 失败测试（+4）**

```php
test('编辑页可访问并预填', function () {
    $user = User::factory()->create(['name' => '待编辑']);

    $response = $this->actingAs(adminUser())->get('/admin/user/edit/'.$user->id);

    $response->assertOk()->assertSee('待编辑')->assertSee($user->email);
});

test('更新用户成功且密码留空不改动', function () {
    $user = User::factory()->create();

    $response = $this->actingAs(adminUser())->put('/admin/user/edit/'.$user->id, [
        'name' => '改名了',
        'email' => $user->email,
        'password' => '',
        'password_confirmation' => '',
        'status' => '1',
    ]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '更新成功');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => '改名了']);
    $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    $this->assertAuthenticated(); // 旧密码仍可登录 = 密码未被清掉
});

test('单删用户成功', function () {
    $user = User::factory()->create();

    $response = $this->actingAs(adminUser())->delete('/admin/user', ['id' => $user->id]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '删除成功');
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('批量删除用户成功', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();
    $keeper = User::factory()->create();

    $response = $this->actingAs(adminUser())->delete('/admin/user', ['ids' => [$a->id, $b->id]]);

    $response->assertRedirect('/admin/user')->assertSessionHas('status', '删除成功');
    $this->assertDatabaseMissing('users', ['id' => $a->id]);
    $this->assertDatabaseMissing('users', ['id' => $b->id]);
    $this->assertDatabaseHas('users', ['id' => $keeper->id]);
});
```

> 注：`$this->delete('/admin/user', ['id'=>...])` 的数组参数会作为请求体发送；FormRequest 校验 destroy 用裸 Request 即可（id/ids 二选一，服务端 `removeByIds` 收数组）。

- [ ] **Step 2: 确认失败**

- [ ] **Step 3: UserUpdateRequest**

同 UserStoreRequest，差异：email 规则 `unique:users,email,{id}`（用 `Rule::unique('users','email')->ignore($this->route('user'))`——路由参数名以 gen 产物 `{id}` 为准，实现时核对 route:list 输出后取参名）；password `nullable` + `confirmed`（留空跳过）。规则内拿路由参数：`$this->route('id')`（gen 路径是 `/user/edit/{id}`）。

- [ ] **Step 4: 控制器 +edit/update/destroy + OA + gen:route**

```php
    #[OA\Get(path: '/user/edit/{id}', summary: '编辑用户页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function edit(int $id): Renderable
    {
        $user = User::findOrFail($id);

        return view('admin::user.edit', [
            'user' => $user,
            'statusOptions' => [...同 create...],
        ]);
    }

    #[OA\Put(path: '/user/edit/{id}', summary: '更新用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function update(UserUpdateRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $data = [
            'name' => $request->string(UserUpdateRequest::getName)->toString(),
            'email' => $request->string(UserUpdateRequest::getEmail)->toString(),
            'status' => (int) $request->string(UserUpdateRequest::getStatus)->toString(),
        ];
        if ($request->filled(UserUpdateRequest::getPassword)) {
            $data['password'] = $request->string(UserUpdateRequest::getPassword)->toString();
        }
        $user->fill($data)->save();

        return redirect()->route('admin.user.index')->with('status', '更新成功');
    }

    #[OA\Delete(path: '/user', summary: '删除用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function destroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if ($ids === []) {
            $ids = [$request->integer('id')];
        }
        $ids = array_map(intval(...), array_filter($ids));

        User::destroy($ids);

        return redirect()->route('admin.user.index')->with('status', '删除成功');
    }
```

（PUT 请求带 body：`$this->put()` 测试传数组即 body；FormRequest 校验读 `$request->post()`/input 均可）

```bash
php artisan gen:route && vendor/bin/pint app/Modules/Admin/Routes/route.gen.php --format agent
```

- [ ] **Step 5: edit.blade.php**（create 复制改：action `route('admin.user.update', $user->id)`、预填 old()/用户值、密码占位提示「留空则不修改」）

- [ ] **Step 6: index 页行删与批量接线**

- 占位「删除」文本替换为行删 form（onsubmit confirm + @method('DELETE') + 隐藏 id）
- 批量表单 action 换 `{{ route('admin.user.destroy') }}`（若 gen 未命名则用 `/admin/user` 字面量——gen 只给 GET 命名，POST/PUT/DELETE 无名，**直接用字面量 `/admin/user`**）
- 编辑链接换 `{{ route('admin.user.edit', $user['id']) }}`

- [ ] **Step 7: 测试通过 + 回归 + pint**

---

### Task 5: 收尾验证

- [ ] 全量测试（预期 29 + 4 + 2 + 4 = 39 例全绿）
- [ ] pint --dirty
- [ ] gen:route 幂等（再跑无 diff）
- [ ] 冒烟：登录 test@example.com → 列表筛选/分页 → 新建 → 编辑改状态为禁用 → 该用户登录被拒 → 行删/批量删
- [ ] git status 与文件结构一致；不提交

---

## 附录：手写分页链接（CurdRepository::page 返回数组时）

```blade
@if (($users['lastPage'] ?? 1) > 1)
<nav class="d-flex justify-content-between align-items-center mt-3">
    <span class="text-muted small">共 {{ $users['total'] }} 条</span>
    <ul class="pagination pagination-sm mb-0">
        @for ($p = 1; $p <= $users['lastPage']; $p++)
            <li class="page-item {{ $p === $users['currentPage'] ? 'active' : '' }}">
                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">{{ $p }}</a>
            </li>
        @endfor
    </ul>
</nav>
@endif
```

（键名 lastPage/currentPage 以 Task 2 Step 1 实测为准调整；pageSize select 用 `fullUrlWithQuery(['pageSize' => ...])` 刷新）

## Self-Review 记录

- **Spec 覆盖**：§2 数据层 ↔ Task 1（迁移/枚举/模型/工厂/登录拦截）；§3 路由 ↔ Task 2-4 分批 gen；§4 控制器校验 ↔ 各任务 Request 全量代码；§5 视图 ↔ Task 2-4（四区/表单/删除接线）；§6 菜单 ↔ Task 1 迁移内修正；§7 测试 10 例 ↔ 各任务测试代码齐备；§8 验收 ↔ Task 5
- **占位符扫描**：无 TBD；「以实测为准」两处（page 返回结构、路由参数名）均附验证命令与两套适配方案；Task 2 幽灵路由风险已在任务头部修正为分批 gen 策略；临时字面量链接有明确替换步骤（Task 3 Step 6、Task 4 Step 6）
- **类型一致性**：UserStatusEnum int-backed 在迁移(default 1)、工厂(->value)、控制器((int) 收敛)、视图((int) 比较)四处统一；`admin.user.*` 路由名与 gen 派生规则一致（GET 才有名，写路由用字面量已在 Task 4 注明）
