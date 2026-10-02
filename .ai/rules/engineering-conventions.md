# 工程落地与编码军规（Engineering Conventions）

完整背景与目的见 [docs/technical-design.md](../../docs/technical-design.md) 第 3 章。本文件是执行摘要，与工具链（`phpkg/laravel-devtools` / `phpkg/laravel-foundation`）实际行为保持一致。

## 1. 迁移文件按领域组织与表/字段注释规范

- 迁移文件必须**按领域集中**（`create_user_domain_tables.php`、`create_content_domain_tables.php`…），严禁按单表无节制新建。
- 每个表必须声明 `$table->comment('XXX表');`；所有字段必须带简洁 comment。
- 枚举字段注释格式**严格**为 `状态：1-启用，2-禁用`（`描述：值1-标签1，值2-标签2`，全角冒号/逗号、半角连字符），供 `php artisan gen:enums` 解析生成 Enum 类。注意命令是 `gen:enums`（复数）。
- 标准命名例外：认证框架表（`users`、`password_reset_tokens`，devtools `exclude_tables` 依赖）、RBAC 标准表（`roles`、`permissions`、`user_roles`、`role_permissions`，对齐 spatie/laravel-permission 惯例）与配置表 `settings`（对齐 spatie/laravel-settings 惯例）保留通用命名；其余业务表用领域前缀（`site_banners`、`user_audit_logs`、`content_posts`…）。

## 2. 服务层与领域生成代码防腐隔离

- `app/Domains/{Domain}/`（Models / Entities / **Repositories** / Services / Requests / Responses / Controllers）为 DevTools 生成物，**严禁手工侵入修改**，重新生成会覆盖。
- 手写逻辑统一放 `app/Services/{Domain}/`（跨表组装、跨领域协同、业务计算），通过 DI 消费 Domains 的 Services。
- Domains 之间禁止横向调用；跨领域协同只发生在 `app/Services`。
- `app/Models/User.php` 是认证边界模型（guard provider），与生成的领域模型映射同一张 `users` 表，职责分离，二者不可合并。

## 3. 数据接口按业务实体控制器聚合

- 严禁单动作控制器；相关动作聚合在一个控制器中（如 `PostController`: index/store/update/destroy/publish/offline）。

## 4. OpenAPI 注解与 DTO 规范

- 所有控制器公共方法首个 Attribute 必须是 OA HTTP 动词注解，且必须声明 `path` 与 `summary` 命名参数。
- 入参/出参严禁裸数组：入参用模块 `Requests/`，出参用模块 `Responses/` 的强类型 DTO。

## 5. 模块视图与路由就近定义（双交付层）

- **Web 层** `app/Modules/{Admin,Portal,User}/`：Blade 视图在 `Modules/{Module}/Views/`，经 `loadViewsFrom` 注册为 `view('{module}::xxx')`；路由入口 `Routes/route.php`。
- **API 层** `app/Api/{Admin,Common,Portal,User}/`：纯 JSON 接口（`{code,message,data}` 契约），路由入口 `Routes/route.php`。
- 路由装载点：`routes/web.php` glob `app/Modules/*/Routes/route.php`；`routes/api.php` glob `app/Api/*/Routes/route.php`（`/api` 前缀 + api 中间件组）。`app/Api/Admin/Routes/route.php` 另行挂载 `app/Domains/*/Routes/route.gen.php`（领域 CRUD 接口归 Admin API）。
- `route.gen.php` 由 `gen:route` 生成，禁止手改；`route.php` 只做分组（prefix/name/middleware/require），禁止散落定义路由。

## 6. 任务完成收尾三部曲

每次编码/重构交付前按序执行：

```bash
php artisan gen:route
php artisan optimize
vendor\bin\pint.bat app
```

代码生成流水线脚本：`scripts/codegen.bat`（常规再生成，不动数据库）；`scripts/codegen-init.bat`（**清库初始化**，含 `migrate:fresh --seed`，仅限本地）。
