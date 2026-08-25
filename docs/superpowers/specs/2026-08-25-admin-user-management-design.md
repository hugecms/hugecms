# Admin 用户管理模块设计

- 日期：2026-08-25
- 状态：已确认（四决策：Blade 服务端渲染 / 独立编辑页 / confirm 行删+复选批量删 / 加 status 字段）
- 前置：认证模块、菜单地基、antd 风格布局（均已上线）

## 1. 决策记录

| 决策点 | 结论 |
|---|---|
| 数据交互 | 服务端渲染 Blade（GET 查询串分页筛选），不走已有 token API |
| 新建/编辑 | 独立页面 `/admin/user/create`、`/admin/user/edit/{id}` |
| 删除 | 行内 confirm() 删除 + 复选框批量删除（一个 DELETE 端点收 id/ids[]） |
| status 字段 | 本次新增（tinyint 1 启用/0 禁用），含禁用登录拦截 |
| 权限 | 菜单点亮但无 RBAC 校验（中间件属下个子项目） |

## 2. 数据层

### 2.1 迁移 `add_status_to_users_table`

```php
Schema::table('users', function (Blueprint $table) {
    $table->tinyInteger('status')->default(1)->comment('状态：1 启用 0 禁用')->after('remember_token');
});
// 存量回填
DB::table('users')->whereNull('status')->orWhere('status', '!=', 1)->update(['status' => 1]);
```

### 2.2 枚举 `App\Enums\UserStatusEnum`

```php
enum UserStatusEnum: int
{
    case Enabled = 1;
    case Disabled = 0;

    public function label(): string  // 启用 / 禁用
}
```

### 2.3 模型与工厂

- `User`：fillable += `status`；casts += `'status' => UserStatusEnum::class`；追加 `isEnabled(): bool` 便捷方法
- `UserFactory`：definition 加 `'status' => UserStatusEnum::Enabled->value`；新增 `disabled()` state
- `LoginRequest::authenticate()`：attempt 成功后 `if (! $user->isEnabled()) { Auth::logout(); throw ValidationException(...'账号已被禁用'); }`

## 3. 路由（Admin 模块，OA 属性 + gen:route）

| 方法 | 路径 | 控制器@方法 | 命名 |
|---|---|---|---|
| GET | /admin/user | UserController@index | admin.user.index |
| GET | /admin/user/create | UserController@create | admin.user.create |
| POST | /admin/user | UserController@store | — |
| GET | /admin/user/edit/{id} | UserController@edit | admin.user.edit |
| PUT | /admin/user/edit/{id} | UserController@update | — |
| DELETE | /admin/user | UserController@destroy | — |

> gen:route 自动派生命名；菜单表「用户管理」补 route=`admin.user.index`（种子更新 + 存量库 update）。

## 4. 控制器与校验

`App\Modules\Admin\Controllers\UserController`（模块内新控制器，与 API 层同名分属不同命名空间）：

- `index(UserIndexRequest)`：读 email/name/status/page/pageSize，组装 condition（name/email 用 `['name','like',"%{$v}%"]`），`UserService->page($condition, $page, $pageSize)` 返回分页数组；view 变量：`users(pageinator), filters, statusOptions`
- `create()/edit($id)`：渲染表单视图（edit 用 `UserService->getOneById` 取数）
- `store(UserStoreRequest)`：`User::create`（password 由模型 hashed cast）；成功 redirect admin.user.index + status「创建成功」
- `update(UserUpdateRequest, $id)`：查无此用户 404；密码留空则不更新（`array_filter` 剔除空 password）；成功 redirect + status「更新成功」
- `destroy(Request)`：收 `id`（单删）或 `ids[]`（批量），`UserService->removeByIds`；redirect + status「删除成功」

FormRequest（`App\Modules\Admin\Requests\`，中文消息沿用约定）：

- `UserIndexRequest`：email nullable email；name nullable string max:50；status nullable in:0,1；page nullable integer min:1；pageSize nullable integer in:10,20,50
- `UserStoreRequest`：name required max:50；email required email unique:users；password required confirmed Password::defaults()；status required in:0,1
- `UserUpdateRequest`：同 Store 但 email `unique:users,email,{id}`、password `nullable confirmed`（留空不改）

## 5. 视图（antd 四区，复用 hugecms-admin.css 令牌）

- `user/index.blade.php`：标题区（页头 + 新建按钮）→ 筛选区 card → 列表区 card（table：复选列/ID/用户名/邮箱/状态徽章/注册时间/操作）→ 分页区（links() + 共N条 + pageSize select）
- `user/create.blade.php`、`user/edit.blade.php`：标题区 + 表单 card（name/email/password(编辑留空不改)/确认密码/status radio 启用|禁用），错误回显 is-invalid
- 删除：行内 `<form onsubmit="return confirm('确认删除该用户？')">` + `@method('DELETE')`；批量：全选 checkbox + 「批量删除」按钮提交选中 ids[]
- JS（少量，原生）：全选/联动、批量按钮计数显隐——放 `@section('scripts')`
- 状态徽章：`Enabled` → `badge rounded-pill bg-success`；`Disabled` → `badge rounded-pill bg-secondary`

## 6. 菜单与种子

- `MenuSeeder`：「用户管理」加 `'route' => 'admin.user.index'`
- 存量库同步：新迁移文件内或独立 data migration（`DB::table('menus')->where('name','用户管理')->update(['route'=>'admin.user.index'])`）——并入 status 迁移同文件，减少迁移数
- `PermissionSeeder`：已有 `admin.user.*` 权限点，无需新增

## 7. 测试 `tests/Feature/Admin/UserManagementTest.php`（约 9 例）

1. 列表页可访问且渲染表格
2. name 筛选模糊命中 / email 精确命中
3. status 筛选：禁用用户过滤正确
4. 新建用户成功入库 + 自动登录态不受影响（管理端不登录）
5. 新建重复邮箱失败回显错误
6. 编辑页可访问且预填；更新（含改密码）成功
7. 单删成功（id）
8. 批量删成功（ids[]）
9. 禁用用户登录被拒（LoginRequest 拦截）
10. 侧边栏「用户管理」为 active 且可点击（非 disabled）

## 8. 验收

- `php artisan test --compact` 全绿；pint 通过
- 手动：列表筛选/分页、新建→编辑→禁用→该用户登录被拒→删除，全流程通
