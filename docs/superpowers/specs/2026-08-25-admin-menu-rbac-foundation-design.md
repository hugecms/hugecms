# Admin 菜单与 RBAC 地基设计

- 日期：2026-08-25
- 状态：已确认（方案 A、范围=只做地基、角色三表一并建）
- 范围：菜单/权限/角色五表迁移 + 种子 + Admin 侧边栏数据库渲染；RBAC 中间件与角色管理页面为下个子项目

## 1. 背景与决策

HugeCMS 后台（`/admin/*`，`auth` 保护）目前侧边栏只有一个硬编码的「仪表盘」。本设计建立菜单导航与 RBAC 的数据地基：

| 决策点 | 结论 |
|---|---|
| 菜单存储 | 数据库表（menus），可后台配置 |
| 菜单与权限 | **分表**：menus=导航展示，permissions=行为许可，命名约定呼应，无外键耦合 |
| 角色三表 | 本次一并建表（roles / role_user / role_permission），不写消费逻辑 |
| 范围 | 只做地基：不建菜单管理页面（等 RBAC 上线后保护），不写权限校验中间件 |
| 代码生成 | Model/Entity/Repository/Service 由 `gen:model/dao/entity/service` 按表生成，遵循项目分层 |

## 2. 数据库设计

### 2.1 表结构

```php
// menus：后台导航树（邻接表，parent_id 平铺）
Schema::create('menus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('parent_id')->nullable()->index()->constrained('menus')->nullOnDelete();
    $table->string('name', 50);                 // 菜单名（如「用户管理」）
    $table->string('icon', 50)->default('');    // 图标类名（预留，渲染层暂用文本）
    $table->string('route', 100)->default('');  // 命名路由（如 admin.dashboard）；分组为空
    $table->unsignedSmallInteger('sort')->default(0); // 组内排序，升序
    $table->boolean('visible')->default(true);  // 是否显示（隐藏≠无权限）
    $table->timestamps();
    $table->unique(['parent_id', 'name']);      // 同层级不重名
});

// permissions：权限点（行为许可，独立于菜单）
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100)->unique();      // 权限标识（如 admin.user.destroy）
    $table->string('title', 50);                // 中文名（如「删除用户」）
    $table->string('module', 50)->default('');  // 归属模块（如 user）
    $table->timestamps();
});

// roles：角色
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name', 50)->unique();       // 角色标识（如 super-admin）
    $table->string('title', 50);                // 中文名（如「超级管理员」）
    $table->timestamps();
});

// role_user：用户-角色 pivot
Schema::create('role_user', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->primary(['role_id', 'user_id']);
});

// role_permission：角色-权限 pivot
Schema::create('role_permission', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
    $table->primary(['role_id', 'permission_id']);
});
```

设计说明：
- `menus.parent_id` 外键 `nullOnDelete`：删父菜单不级联删子菜单（子菜单上提为顶级，由管理页清理）——比级联删更安全
- `unique(['parent_id','name'])`：MySQL 中 NULL 不参与唯一约束，同层级重名约束由应用层（seeder/未来管理页）兜底
- 权限点命名约定：`admin.<模块>.<动作>`（menu 的 route 用命名路由）；二者通过约定呼应，无外键
- pivot 复合主键，无自增 id，无时间戳（RBAC 惯例）

### 2.2 种子数据

`MenuSeeder`：
```
仪表盘                    → route: admin.dashboard
内容管理（分组，无路由）
  ├─ 文章管理             → （占位，route 空，visible:true——页面下个子项目建）
  └─ 栏目管理             → （占位，同上）
用户与权限（分组）
  ├─ 用户管理             → route: admin.user.index（占位）
  ├─ 角色管理             → （占位）
  └─ 菜单管理             → （占位）
系统管理（分组）
  ├─ 系统设置             → （占位）
  └─ 操作日志             → （占位）
```

`PermissionSeeder`：与菜单同构的权限点 + 无菜单对应的接口权限（`admin.user.search/store/update/destroy` 已有 API）+ `admin.dashboard`。

`RoleSeeder`：`super-admin`（超级管理员），关联全部权限点。

`DatabaseSeeder` 依次调用三者。

### 2.3 生成代码层

迁移后执行：
```
php artisan gen:model --table=menus        → Menu 模型（含 Fillable/Hidden 属性、casts）
php artisan gen:dao --table=menus          → MenuRepository
php artisan gen:entity --table=menus       → MenuEntity
php artisan gen:service --table=menus      → MenuService
（permissions/roles 同理；pivot 表不生成——无独立 CRUD 语义）
```

> 注：以上命令选项以 `--help` 实测为准；`devtools.exclude_tables` 需加入五张新表吗——**不加**：排除列表是「框架基础设施表」，业务表应保留生成能力，菜单/权限属业务表。

## 3. Admin 布局改造（侧边栏数据库渲染）

`app/Modules/Admin/Views/layout.blade.php` 侧边栏改为：

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
                            <a href="{{ $child['route'] ? route($child['route']) : '#' }}" class="nav-link text-white py-1">
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

`MenuService::getSidebarMenus(): array`：查 `menus where visible=true order by sort`，内存组树，返回 `[ ['id','name','icon','route','children'=>[...]] ]`。有路由的顶级菜单（仪表盘）平铺输出；分组输出 children。route 为空的子项暂渲染 `#`（占位页面后续接入）。

> Blade 里直接 `app(MenuService::class)` 而非 View Composer——单处使用，避免过度设计；若将来 User 模块也要菜单再抽 Composer。

## 4. 测试策略

| 文件 | 覆盖 |
|---|---|
| `tests/Feature/Admin/SidebarMenuTest.php` | 种子后 `/admin/dashboard` 页面含「内容管理」「用户管理」等侧边栏文本；visible=false 的菜单不出现 |
| `tests/Unit/MenuServiceTest.php` | 组树结构正确（分组 children 数、排序、visible 过滤）——纯内存逻辑，无需 HTTP |

Seeder 在 `RefreshDatabase` 下自动运行（`DatabaseSeeder` 链路），Feature 测试天然有数据。

## 5. 明确不做（后续子项目）

- 菜单管理页面（CRUD）——待 RBAC 保护
- 权限校验中间件（`app/Http/Middleware/Auth.php` 实装）与角色分配 UI
- 菜单缓存（当前每请求一次查询，菜单量级下无压力；RBAC 过滤上线时一并做「按权限过滤菜单」+ 缓存）
- 操作日志表

## 6. 验收标准

1. `php artisan migrate` 五表创建成功；`db:seed` 种子写入无错
2. gen 全套生成（Model/Entity/Repository/Service × 3 业务表）
3. `/admin/dashboard` 侧边栏从数据库渲染，显示种子菜单树
4. 测试全绿（新增 SidebarMenuTest 2 例 + MenuServiceTest 组树断言）
5. pint 通过
