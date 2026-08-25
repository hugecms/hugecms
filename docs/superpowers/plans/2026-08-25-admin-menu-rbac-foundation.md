# Admin 菜单与 RBAC 地基实现计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 建立后台菜单导航与 RBAC 的数据地基：五表迁移 + 种子 + gen 代码层 + Admin 侧边栏数据库渲染。

**Architecture:** 迁移先行（menus/permissions/roles/role_user/role_permission），`gen:model/dao/entity/service` 从表生成代码层（项目既定流水线），`MenuService::getSidebarMenus()` 内存组树供 Admin 布局渲染。权限校验中间件与角色管理页为下个子项目，本次不写消费逻辑。

**Tech Stack:** Laravel 13 迁移与 seeder、Eloquent、phpkg/laravel-devtools 代码生成、Pest 5、Blade。

**Spec:** `docs/superpowers/specs/2026-08-25-admin-menu-rbac-foundation-design.md`

## Global Constraints

- 菜单与权限分表，无外键耦合；命名约定呼应（`admin.<模块>.<动作>`）
- 五表均业务表，**不加入** `config/devtools.php` 的 `exclude_tables`
- pivot 表（role_user/role_permission）不生成代码层（无独立 CRUD 语义）
- gen 命令生成后必须跟一次 `vendor/bin/pint`（归一生成器 FQCN 风格，与仓库一致——上轮已验证该组合幂等）
- `menus.parent_id` 外键 `nullOnDelete`（不级联删子菜单）
- 权限点命名：`admin.<模块>.<动作>`；菜单 route 存命名路由字符串
- 每任务完成跑 `php artisan test --compact` 与 `vendor/bin/pint --dirty --format agent`
- 本会话不执行任何 git commit（用户指示，改动留工作区供审查）

## 文件结构总览

```
创建：
  database/migrations/2026_08_25_000100_create_menus_table.php
  database/migrations/2026_08_25_000200_create_permissions_table.php
  database/migrations/2026_08_25_000300_create_roles_table.php
  database/migrations/2026_08_25_000400_create_role_user_table.php
  database/migrations/2026_08_25_000500_create_role_permission_table.php
  database/seeders/MenuSeeder.php
  database/seeders/PermissionSeeder.php
  database/seeders/RoleSeeder.php
  tests/Feature/Admin/SidebarMenuTest.php
  tests/Unit/MenuServiceTest.php
生成（gen 命令产物）：
  app/Models/Menu.php、app/Models/Permission.php、app/Models/Role.php
  app/Entities/MenuEntity.php、app/Entities/PermissionEntity.php、app/Entities/RoleEntity.php
  app/Repositories/MenuRepository.php、app/Repositories/PermissionRepository.php、app/Repositories/RoleRepository.php
  app/Services/MenuService.php（生成后手写扩展 getSidebarMenus()）、app/Services/PermissionService.php、app/Services/RoleService.php
修改：
  database/seeders/DatabaseSeeder.php（调用三个 seeder）
  app/Modules/Admin/Views/layout.blade.php（侧边栏查库渲染）
```

---

### Task 1: 五表迁移

**Files:**
- Create: `database/migrations/2026_08_25_000100_create_menus_table.php`（含其余四表，共 5 个文件）

**Interfaces:**
- Produces: 五张表结构，Task 2 seeder 与 Task 3 gen 依赖

- [ ] **Step 1: 创建迁移文件（5 个）**

```bash
php artisan make:migration create_menus_table --no-interaction
php artisan make:migration create_permissions_table --no-interaction
php artisan make:migration create_roles_table --no-interaction
php artisan make:migration create_role_user_table --no-interaction
php artisan make:migration create_role_permission_table --no-interaction
```

- [ ] **Step 2: 编写迁移内容**

menus（其余四表见下方代码块）：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->index()->constrained('menus')->nullOnDelete();
            $table->string('name', 50);
            $table->string('icon', 50)->default('');
            $table->string('route', 100)->default('');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
            $table->unique(['parent_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
```

permissions：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('title', 50);
            $table->string('module', 50)->default('');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
```

roles：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('title', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
```

role_user：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
```

role_permission：

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};
```

- [ ] **Step 3: 验证迁移**

Run: `php artisan migrate`
Expected: 五表创建成功（sqlite :memory: 由 RefreshDatabase 自动建；此处跑开发库确认 SQL 无误后 `migrate:rollback` 回滚，保持开发库与 git 状态一致——**注意**：如开发库有数据请先确认；rollback 仅回滚本次五表）

- [ ] **Step 4: 格式化（不提交）**

```bash
vendor/bin/pint --dirty --format agent
```

---

### Task 2: 种子数据

**Files:**
- Create: `database/seeders/MenuSeeder.php`、`database/seeders/PermissionSeeder.php`、`database/seeders/RoleSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

**Interfaces:**
- Consumes: Task 1 五表
- Produces: 种子菜单树（含分组与占位子项）、权限点集合、super-admin 角色及其全部权限关联——Task 4 渲染测试依赖

- [ ] **Step 1: 编写 MenuSeeder**

```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * 后台初始菜单树（route 为命名路由；占位项 route 留空）
     */
    public function run(): void
    {
        $dashboard = Menu::create([
            'name' => '仪表盘',
            'route' => 'admin.dashboard',
            'sort' => 0,
        ]);

        $content = Menu::create([
            'name' => '内容管理',
            'sort' => 10,
        ]);
        Menu::create(['parent_id' => $content->id, 'name' => '文章管理', 'sort' => 10]);
        Menu::create(['parent_id' => $content->id, 'name' => '栏目管理', 'sort' => 20]);

        $userPerm = Menu::create([
            'name' => '用户与权限',
            'sort' => 20,
        ]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '用户管理', 'sort' => 10]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '角色管理', 'sort' => 20]);
        Menu::create(['parent_id' => $userPerm->id, 'name' => '菜单管理', 'sort' => 30]);

        $system = Menu::create([
            'name' => '系统管理',
            'sort' => 30,
        ]);
        Menu::create(['parent_id' => $system->id, 'name' => '系统设置', 'sort' => 10]);
        Menu::create(['parent_id' => $system->id, 'name' => '操作日志', 'sort' => 20]);
    }
}
```

- [ ] **Step 2: 编写 PermissionSeeder**

```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * 权限点（含已有 Admin API 的接口权限）
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'admin.dashboard', 'title' => '访问仪表盘', 'module' => 'dashboard'],
            ['name' => 'admin.user.index', 'title' => '查看用户列表', 'module' => 'user'],
            ['name' => 'admin.user.search', 'title' => '搜索用户', 'module' => 'user'],
            ['name' => 'admin.user.store', 'title' => '创建用户', 'module' => 'user'],
            ['name' => 'admin.user.update', 'title' => '更新用户', 'module' => 'user'],
            ['name' => 'admin.user.destroy', 'title' => '删除用户', 'module' => 'user'],
            ['name' => 'admin.menu.index', 'title' => '查看菜单', 'module' => 'menu'],
            ['name' => 'admin.role.index', 'title' => '查看角色', 'module' => 'role'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
```

- [ ] **Step 3: 编写 RoleSeeder**

```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::create([
            'name' => 'super-admin',
            'title' => '超级管理员',
        ]);

        $role->permissions()->attach(\App\Models\Permission::pluck('id'));
    }
}
```

> 注：`$role->permissions()` belongsToMany 关系在 Task 3 gen Model 后才存在。**执行顺序**：Task 3 生成 Model 后本 Seeder 才可运行。计划内将 RoleSeeder 的验证（`db:seed`）安排在 Task 3 之后（Task 2 只写文件不跑 seed），DatabaseSeeder 修改也放 Task 3 末尾一并验证。

- [ ] **Step 4: 修改 DatabaseSeeder**

```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
```

---

### Task 3: gen 代码层生成

**Files:**
- Generate: `app/Models/{Menu,Permission,Role}.php`、`app/Entities/*`、`app/Repositories/*`、`app/Services/*`

**Interfaces:**
- Consumes: Task 1 表结构、Task 2 seeder
- Produces: `MenuService` 等全套；`Role::permissions()` belongsToMany、`Menu::children/parent` 关系（gen 产物按工具实际输出为准，手写补充部分见 Task 4）

- [ ] **Step 1: 生成 Model（3 表）**

```bash
php artisan gen:model --table=menus
php artisan gen:model --table=permissions
php artisan gen:model --table=roles
```

（命令交互选项按 `--help` 提示选择；若 gen:model 按 prefix 扫描全库生成而非单表，则接受全套产物并核对五表中新表是否齐备）

- [ ] **Step 2: 生成 Repository/Entity/Service（3 表）**

```bash
php artisan gen:dao --table=menus
php artisan gen:entity --table=menus
php artisan gen:service --table=menus
php artisan gen:dao --table=permissions
php artisan gen:entity --table=permissions
php artisan gen:service --table=permissions
php artisan gen:dao --table=roles
php artisan gen:entity --table=roles
php artisan gen:service --table=roles
```

- [ ] **Step 3: pint 归一 + 补充关系**

```bash
vendor/bin/pint app/ --format agent
```

核对生成的 Model 是否含关系方法；**若无**则手写补充（Laravel 惯例，参考 `Menu::children(): HasMany`、`Menu::parent(): BelongsTo`、`Role::permissions(): BelongsToMany`、`Permission::roles(): BelongsToMany`、`User::roles(): BelongsToMany`——User 模型需加 `roles()` 关系，pivot 表 `role_user`）。

- [ ] **Step 4: 验证种子链路**

```bash
php artisan db:seed --no-interaction
```

Expected: 三个 seeder 依次执行无错；sqlite 开发库（或 mysql，按 .env）中 menus 10 行、permissions 8 行、roles 1 行、role_permission 8 行

- [ ] **Step 5: 跑既有测试确认无回归**

Run: `php artisan test --compact`
Expected: 23/23 PASS（种子经 RefreshDatabase 自动运行，AuthenticationTest 等不受影响）

---

### Task 4: MenuService 组树 + 侧边栏渲染

**Files:**
- Modify: `app/Services/MenuService.php`（生成骨架上扩展）
- Modify: `app/Modules/Admin/Views/layout.blade.php`
- Test: `tests/Unit/MenuServiceTest.php`、`tests/Feature/Admin/SidebarMenuTest.php`

**Interfaces:**
- Consumes: `Menu` 模型、种子数据
- Produces: `MenuService::getSidebarMenus(): array`（`[['id','name','icon','route','children'=>[...]], ...]`，仅 visible，按 sort 升序；顶级有 route 平铺、分组含 children）

- [ ] **Step 1: 写失败测试（Unit）**

`tests/Unit/MenuServiceTest.php`：

```php
<?php

use App\Models\Menu;
use App\Services\MenuService;

test('侧边栏菜单按 sort 升序并正确组树', function () {
    $content = Menu::create(['name' => '内容管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '文章管理', 'sort' => 10]);
    Menu::create(['parent_id' => $content->id, 'name' => '栏目管理', 'sort' => 20]);
    Menu::create(['name' => '仪表盘', 'route' => 'admin.dashboard', 'sort' => 0]);

    $result = app(MenuService::class)->getSidebarMenus();

    expect($result)->toHaveCount(2)
        ->and($result[0]['name'])->toBe('仪表盘')
        ->and($result[0]['route'])->toBe('admin.dashboard')
        ->and($result[1]['name'])->toBe('内容管理')
        ->and($result[1]['children'])->toHaveCount(2)
        ->and($result[1]['children'][0]['name'])->toBe('文章管理');
});

test('不可见菜单被过滤', function () {
    Menu::create(['name' => '仪表盘', 'route' => 'admin.dashboard', 'sort' => 0]);
    Menu::create(['name' => '隐藏组', 'visible' => false]);

    $result = app(MenuService::class)->getSidebarMenus();

    expect($result)->toHaveCount(1)
        ->and($result[0]['name'])->toBe('仪表盘');
});
```

> 注：Unit 测试默认无 RefreshDatabase（`pest()->extend(...)->in('Feature')` 只作用于 Feature）。本测试直接用 sqlite :memory: 需要 RefreshDatabase——**放 Feature 目录更省事**（沿用 Feature 配置）。裁决：文件放 `tests/Feature/MenuServiceTest.php`（RefreshDatabase 自动建表+种子；种子数据会混入断言基数，需在断言中考虑或先 `Model::query()->delete()` 清场）。

- [ ] **Step 2: 跑测试确认失败**

Run: `php artisan test --compact --filter=MenuServiceTest`
Expected: FAIL——`getSidebarMenus` 方法不存在

- [ ] **Step 3: 实现 getSidebarMenus**

在生成的 `app/Services/MenuService.php` 骨架上添加（保留 gen 产物结构，追加公开方法）：

```php
    /**
     * 后台侧边栏菜单树（仅可见项，按 sort 升序）
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSidebarMenus(): array
    {
        $menus = $this->getRepository()->findByWhere([['visible', '=', true]], ['sort' => 'asc']);

        $grouped = [];
        $children = [];
        foreach ($menus as $menu) {
            if ($menu->parent_id === null) {
                $grouped[$menu->id] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'route' => $menu->route,
                    'children' => [],
                ];
            } else {
                $children[$menu->parent_id][] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'route' => $menu->route,
                ];
            }
        }

        foreach ($children as $parentId => $items) {
            if (isset($grouped[$parentId])) {
                $grouped[$parentId]['children'] = $items;
            }
        }

        return array_values($grouped);
    }
```

> 注：`findByWhere` 方法名以 gen 产物 `CurdRepository`/`CommonService` 实际 API 为准——实现时先查 `vendor/phpkg/laravel-foundation/src/Repositories/CurdRepository.php` 的可用查询方法再写；若无可按条件查询的封装，则直接用 `Menu::query()->where('visible', true)->orderBy('sort')->get()`（Service 层不强制走 Repository 的薄查询）。

- [ ] **Step 4: 跑测试确认通过**

Run: `php artisan test --compact --filter=MenuServiceTest`
Expected: PASS（2 例）

- [ ] **Step 5: 改造 Admin 布局侧边栏 + Feature 测试**

`app/Modules/Admin/Views/layout.blade.php` 的 `<aside>` 内替换为：

```blade
@php
    $menuGroups = app(\App\Services\MenuService::class)->getSidebarMenus();
@endphp
<ul class="nav nav-pills flex-column gap-1">
    @foreach ($menuGroups as $group)
        @if ($group['route'] !== '')
            <li class="nav-item">
                <a href="{{ route($group['route']) }}" class="nav-link text-white">{{ $group['name'] }}</a>
            </li>
        @else
            <li class="nav-item">
                <div class="text-white-50 small fw-semibold text-uppercase px-2 py-1">{{ $group['name'] }}</div>
                <ul class="nav nav-pills flex-column gap-1">
                    @foreach ($group['children'] as $child)
                        <li class="nav-item">
                            <a href="{{ $child['route'] !== '' ? route($child['route']) : '#' }}" class="nav-link text-white py-1">
                                {{ $child['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach
</ul>
```

`tests/Feature/Admin/SidebarMenuTest.php`：

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('后台侧边栏渲染种子菜单树', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk()
        ->assertSee('仪表盘')
        ->assertSee('内容管理')
        ->assertSee('文章管理')
        ->assertSee('用户与权限')
        ->assertSee('系统管理');
});

test('不可见菜单不出现在侧边栏', function () {
    $user = User::factory()->create();
    \App\Models\Menu::where('name', '栏目管理')->update(['visible' => false]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk()->assertDontSee('栏目管理');
});
```

- [ ] **Step 6: 全量测试**

Run: `php artisan test --compact`
Expected: 全部 PASS（23 + 2 + 2 = 27）

- [ ] **Step 7: pint**

```bash
vendor/bin/pint --dirty --format agent
```

---

### Task 5: 收尾验证

- [ ] **Step 1: 全量测试**

Run: `php artisan test --compact`
Expected: 全部 PASS

- [ ] **Step 2: 冒烟验证**

```bash
php artisan serve --port=8899 &
sleep 2
curl -s -c /tmp/cj -b /tmp/cj http://127.0.0.1:8899/login …（登录拿 session 后 GET /admin/dashboard 验证侧边栏含菜单文本）
```

（或用 `php artisan tinker` 直接调 `app(MenuService::class)->getSidebarMenus()` 断言结构）

- [ ] **Step 3: 状态确认**

`git status` 确认改动文件与本计划文件结构一致，无多余产物；**不提交**（用户指示）

---

## Self-Review 记录

- **Spec 覆盖**：§2.1 五表 ↔ Task 1（逐字段一致）；§2.2 种子 ↔ Task 2（菜单树 10 项、权限 8 点、super-admin 全关联）；§2.3 生成 ↔ Task 3；§3 渲染 ↔ Task 4（布局代码逐行取自 spec）；§4 测试 ↔ Task 4（ServiceTest 2 例 + SidebarMenuTest 2 例）；§5 不做项均无对应任务
- **占位符扫描**：两处「以实际 API 为准」是探索式步骤（gen 产物与 CurdRepository API 不可预知），已给出替代方案与验证命令，非 TBD
- **类型一致性**：`getSidebarMenus(): array` 返回结构在 Task 4 测试/实现/布局三处一致（id/name/icon/route/children）；seeder 字段与迁移字段一致；RoleSeeder 依赖 Task 3 的 `permissions()` 关系已在 Task 2 注明执行顺序约束
