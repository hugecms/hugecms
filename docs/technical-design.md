# HugeCMS 技术实现文档（Technical Design）

| 项目 | 内容 |
| --- | --- |
| 产品名称 | HugeCMS 内容管理系统 |
| 文档版本 | V1.0（草稿） |
| 上游文档 | [产品需求文档 PRD](./prd.md) |
| 创建日期 | 2026-10-02 |
| 文档状态 | 待评审（评审人：研发 / 架构 / 产品） |
| 技术基线 | PHP 8.4 / Laravel 13 / MySQL 8.4 / Vite + Tailwind CSS 4 |

## 变更记录

| 版本 | 日期 | 变更内容 | 作者 |
| --- | --- | --- | --- |
| V1.0 | 2026-10-02 | 初稿：领域划分、数据表设计、模块与路由、横切设计、编码军规收录 | 研发组 |

---

## 1. 文档说明

本文档基于 [PRD](./prd.md) 自底向上完成技术实现设计，范围覆盖 V1.0（MVP）全部功能与 V1.1/V1.2 的结构性预留。核心设计决策：

1. **领域驱动的前后端组织**：业务按领域（Domain）划分为 User / Content / Taxonomy / Media / Setting / Comment / Workflow / Stat / System 九个领域，数据表以领域为前缀命名；
2. **模块化交付界面**：HTTP 入口按模块（Module）组织为 Portal（前台）/ Admin（后台）/ User（个人中心，V1.1）/ Api（开放接口，V1.2）；
3. **生成代码与手工代码防腐隔离**：DevTools 生成 `app/Domains`，手写逻辑收敛于 `app/Services` 与 `app/Modules`；
4. **云原生约束前置**：App Engine 标准环境只读文件系统约束（详见 [部署指南](../deployments/appengine-standard.md)）直接影响缓存、会话、队列、上传的设计选型。

## 2. 总体架构与目录规划

### 2.1 分层架构

```
HTTP 请求
   │
   ▼
app/Modules/{Portal,Admin,User,Api}        交付层：控制器聚合、页面渲染、路由（就近定义）
   │  Request 校验 / Response DTO
   ▼
app/Services/{Domain}                      应用层：跨表组装、跨领域协同、业务计算（手写）
   │  依赖注入
   ▼
app/Domains/{Domain}/Services              领域层：单领域 CRUD 与领域规则（DevTools 生成，禁手改）
   │
   ▼
app/Domains/{Domain}/{Dao,Models,Entities} 数据层：查询封装、Eloquent 模型
   │
   ▼
MySQL 8.4（Cloud SQL）   GCS（媒体文件）   队列/调度（database 驱动）
```

依赖方向自上而下单向依赖；`app/Services` 可跨领域调用 `app/Domains/*/Services`，`app/Domains` 之间不横向调用（跨领域协同一律上提到 `app/Services`）。

### 2.2 领域清单与表前缀

| 领域 | 职责（对应 PRD 章节） | 表前缀 | 版本 |
| --- | --- | --- | --- |
| User | 用户、角色、审计日志（4.5） | `user_` | V1.0 |
| Content | 文章、单页、修订版本（4.2） | `content_` | V1.0 |
| Taxonomy | 分类、标签（4.2.3） | `taxonomy_` | V1.0 |
| Media | 媒体文件、引用关系（4.4） | `media_` | V1.0 |
| Setting | 站点配置、重定向、菜单（4.7） | `setting_` | V1.0 |
| System | 框架基础表（会话/缓存/队列/通知） | Laravel 标准命名 | V1.0 |
| Comment | 评论、敏感词（4.6） | `comment_` | V1.1 |
| Workflow | 审核流转（4.3） | `workflow_` | V1.1 |
| Stat | 访问统计（4.11） | `stat_` | V1.1 |

> Search / Theme / OpenApi 域无独立数据表（搜索基于 MySQL 全文索引，主题基于配置，开放 API 复用 Content/Taxonomy 域），在相应章节单独说明。

### 2.3 模块清单

| 模块 | 目录 | 职责 | 版本 |
| --- | --- | --- | --- |
| Portal | `app/Modules/Portal` | 前台站点：首页/列表/详情/单页/搜索/sitemap | V1.0 |
| Admin | `app/Modules/Admin` | 管理后台：全部管理功能 | V1.0 |
| User | `app/Modules/User` | 读者个人中心（评论、通知、资料） | V1.1 |
| Api | `app/Api` | 开放 API（REST + Token） | V1.2 |

### 2.4 目录结构规划

```
app/
├── Domains/                          # DevTools 生成代码（严禁手工侵入，见军规 3.2）
│   ├── User/
│   │   ├── Models/  Entities/  Dao/  Services/  Requests/  Responses/
│   ├── Content/   …（结构同上）
│   ├── Taxonomy/  Media/  Setting/  Comment/  Workflow/  Stat/
├── Services/                         # 手写应用层（跨领域协同、业务计算）
│   ├── Content/PostManageService.php
│   ├── Media/MediaUploadService.php
│   └── …
├── Modules/                          # 交付层（视图与路由就近定义，见军规 3.5）
│   ├── Admin/
│   │   ├── Controllers/
│   │   ├── Requests/  Responses/
│   │   ├── Views/                    # Blade 视图（命名空间 'admin::'）
│   │   └── Routes/route.php          # 轻量入口，require route.gen.php
│   ├── Portal/  User/  Api/          # 结构同上
├── Http/                             # 仅保留框架必要内容（Kernel 中间件配置等）
├── Providers/                        # AppServiceProvider + 模块注册
└── …

database/migrations/                  # 按领域集中，见军规 3.1
├── create_user_domain_tables.php
├── create_content_domain_tables.php
├── create_taxonomy_domain_tables.php
├── create_media_domain_tables.php
├── create_setting_domain_tables.php
├── create_system_domain_tables.php   # 框架基础表（sessions/cache/jobs…）
└── （V1.1）create_comment_domain_tables.php 等
```

> **对框架默认结构的改造**：删除 Laravel 自带的 `0001_01_01_000000_create_users_table.php`、`0001_01_01_000001_create_cache_table.php`、`0001_01_01_000002_create_jobs_table.php` 三件套，由 `user_domain` 与 `system_domain` 迁移替代（`users` → `user_users`，并同步调整认证配置的 `providers.users.table`）。

---

## 3. 工程落地与编码军规（Engineering Conventions）

> 以下军规为团队强制约定，评审通过后对所有代码提交生效。

### 3.1 迁移文件按领域组织与表/字段注释规范

- **规则**：
  1. 严禁按单表无节制新建 migration 文件。必须**按领域（如 Goods、Trade、User、Supplier）集中创建与维护迁移文件**（例如 `create_goods_domain_tables.php`、`create_trade_domain_tables.php`）。
  2. **Schema 必须包含简洁清晰的表注释**：每个表的迁移定义中必须显式声明 `$table->comment('XXX表');`（例如 `users` 表标注"用户表"、`orders` 表标注"订单表"、`carts` 表标注"购物车表"）。
  3. **字段注释与枚举字段格式规范**：所有字段必须带有简洁的 comment 信息。若为状态或类型枚举字段，描述信息必须严格统一使用形如：**`状态：1-启用，2-不启用`** 格式（格式：`描述：值1-标签1，值2-标签2`，使用冒号与破折号、逗号隔开），以供 `php artisan gen:enum` 工具精准解析并自动生成对应的 PHP Enum 类。
- **目的**：杜绝 `database/migrations` 随着表数增多而产生上百个碎片文件的无限膨胀；同时保障代码生成器（`php artisan gen:xxx`）及数据库字典工具能够精准提取表业务语义与枚举映射，自动生成规范的代码命名与枚举类。
- **HugeCMS 应用**：本项目领域即迁移单位，对应 `create_user_domain_tables.php`、`create_content_domain_tables.php` 等（见 2.4）；枚举注释格式统一为本文档第 5 章各表定义中的写法。

### 3.2 服务层（app/Services）与领域生成代码防腐隔离

- **规则**：
  1. 通过 `php artisan gen:xxx`（DevTools）生成的 `app/Domains/{Domain}/` 基础代码（Model, Entity, Dao/Repository, Service, Request, Response）作为基底资产，**原则上严禁手工侵入修改**。
  2. 复杂的跨表组装、跨领域协同、业务计算等应用层服务，统一在 **`app/Services/{Domain}/`** 中按领域创建，通过继承或依赖注入（DI）消费 `app/Domains/{Domain}/Services`。
- **目的**：代码生成器后续重新执行或覆盖时，不会抹掉应用层手工编写的核心业务代码。
- **HugeCMS 应用**：第 5 章各领域列出的「应用服务」全部落在 `app/Services/{Domain}/`；跨领域调用（如发布文章需失效缓存 + 通知 + 统计）只发生在应用层。

### 3.3 数据接口按业务实体控制器聚合

- **规则**：严禁为每个 API 动作单独创建单动作控制器（Single Action Controller）。相关联的业务动作必须统一聚合在一个控制器中（例如：商品列表 `search`、商品详情 `show`、商品分类 `categories` 统一在 `GoodsController` 中）。
- **目的**：保持路由配置清晰紧凑，控制器职责聚合，大幅度降低文件维护成本。
- **HugeCMS 应用**：控制器清单见第 6 章，如 `Admin\PostController` 聚合 index/store/update/destroy/restore/publish/offline 等，`Portal\PostController` 聚合 home/search/show。

### 3.4 接口文档 OpenAPI 注解与 DTO 规范

- **规则**：
  1. 所有 API 控制器方法必须采用 PHP 8 原生属性 `#[OA\...]`（OpenApi\Attributes）标准注解，声明请求方式、路径、入参、请求体 Schema 与响应结构。
  2. 请求入参 DTO 与响应出参 DTO **严禁使用任意无约束的数组**，必须分别在对应模块的 `Requests/` 与 `Responses/` 目录中单独定义，配合注解实现强类型契约。
- **目的**：接口即文档，前后端与第三方基于同一契约并行开发。
- **HugeCMS 应用**：`Api` 模块（V1.2）严格执行；`Admin`/`Portal` 的 JSON 响应（如媒体上传返回、批量操作返回）同样定义 Response DTO，禁止裸数组。

### 3.5 模块视图与路由就近定义原则

- **规则**：`app/Modules/{Portal,Admin,Seller,Supplier,User}` 各模块的 Blade 视图统一就近存放在 `app/Modules/{Module}/Views/` 目录中，Web 路由统一定义在 `app/Modules/{Module}/Routes/route.php`。
- **服务提供者注册**：在全局服务提供者中自动扫描 `app/Modules/*/Views`，通过 `loadViewsFrom($viewsPath, $moduleName)` 进行视图命名空间注入；在控制器中使用 `view('{module}::xxx')` 进行视图渲染。
- **HugeCMS 应用**：本项目模块集合为 `{Portal, Admin, User, Api}`（Api 无视图）；视图命名空间 `view('admin::posts.index')`、`view('portal::posts.show')`。

### 3.6 Modules 控制器 OpenAPI 注解与 gen:route 自动化路由规范

- **规则**：
  1. `app/Modules/{Portal,Admin,Seller,Supplier,User}` 下的所有控制器公共方法，必须在其首个 Attribute 位置标注标准 OpenAPI HTTP 动词注解（例如 `#[OA\Get(path: '...', summary: '...')]` 或 `#[OA\Post(path: '...', summary: '...')]`）。
  2. 必须严格声明 `path` 与 `summary` 两个命名参数。`path` 为相对于模块根路径的路由路径（如 `path: '/goods'`），`summary` 为该页面或动作的中文简述。
  3. 路由由命令行工具 `php artisan gen:route` 统一自动化扫描并生成至对应模块的 `Routes/route.gen.php` 中。
  4. 各模块的主路由入口 `Routes/route.php` 必须严格遵循轻量化原则，仅负责通过命名空间分组引入生成的路由文件（如 `Route::name('{module}.')->group(function () { require __DIR__.'/route.gen.php'; });`），杜绝手工散落定义路由。
- **目的**：注解即路由事实源，杜绝路由与文档漂移。
- **HugeCMS 应用**：所有 `Admin`/`Portal`/`User`/`Api` 控制器方法均需 `#[OA\Get/Post/Put/Delete(path:…, summary:…)]`；模块级前缀与中间件在 `route.php` 入口统一施加（Admin：`/admin` + auth + role；Portal：`/`；Api：`/api/v1` + token）。

### 3.7 任务完成收尾三部曲自动化执行规范

- **规则**：每次编码或重构任务完成准备向用户交付前，必须在项目根目录下按序自动执行以下三条命令：
  ```bash
  php artisan gen:route
  php artisan optimize
  vendor\bin\pint.bat app
  ```
- **目的**：
  1. 确保新增或修改的模块控制器路由定义即时同步至 `route.gen.php`；
  2. 编译并刷新框架配置、路由与事件缓存，验证无语法或依赖死锁异常；
  3. 统一代码规范格式化，保持代码库整洁一致。

---

## 4. 数据表总览

**命名规范**：业务表 = `{领域前缀}_{实体复数}`（如 `content_posts`）；关联表 = `{前缀}_{实体A}_{实体B}`；全部小写下划线。所有表、字段必须带注释；枚举字段注释遵循军规 3.1 格式。

### 4.1 V1.0（MVP）

| 表名 | 说明 | 所属域 |
| --- | --- | --- |
| users | 用户表 | User |
| user_roles | 角色表 | User |
| password_reset_tokens | 密码重置令牌表 | User |
| user_audit_logs | 操作审计日志表 | User |
| content_posts | 文章表 | Content |
| content_pages | 单页表 | Content |
| taxonomy_categories | 分类表 | Taxonomy |
| taxonomy_tags | 标签表 | Taxonomy |
| taxonomy_post_tag | 文章标签关联表 | Taxonomy |
| media_files | 媒体文件表 | Media |
| media_usages | 媒体引用表 | Media |
| setting_settings | 站点配置表 | Setting |
| sessions / cache / cache_locks / jobs / job_batches / failed_jobs | 框架基础表（标准命名，App Engine database 驱动依赖） | System |

### 4.2 V1.1 / V1.2 预留

| 表名 | 说明 | 所属域 | 版本 |
| --- | --- | --- | --- |
| content_post_revisions | 文章修订版本表 | Content | V1.1 |
| comment_comments | 评论表 | Comment | V1.1 |
| comment_sensitive_words | 敏感词表 | Comment | V1.1 |
| workflow_records | 审核流转记录表 | Workflow | V1.1 |
| setting_redirects | 重定向表 | Setting | V1.1 |
| setting_nav_menus / setting_nav_menu_items | 导航菜单表 / 菜单项表 | Setting | V1.1 |
| stat_view_logs / stat_daily_post_views / stat_daily_site_views | 访问事件表 / 文章日统计表 / 站点日统计表 | Stat | V1.1 |
| notifications | 通知表（Laravel 标准命名） | System | V1.1 |
| user_role_user（多角色 pivot，替代 role_id 单值） | 用户角色关联表 | User | V1.2 |
| personal_access_tokens | API Token 表（Sanctum，标准命名） | System | V1.2 |

---

## 5. 领域模块详细设计

> 字段表中「注释」列即迁移文件中该字段的 `->comment()` 原文；枚举字段注释即 `gen:enum` 的解析源。

### 5.1 User 域（V1.0）

**职责**：账号体系、角色权限（RBAC）、登录安全、操作审计。对应 PRD 4.5（FR-501~505）。

#### 表：users（用户表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 用户ID |
| name | varchar(64) | 昵称 |
| email | varchar(128) UK | 登录邮箱 |
| password | varchar(255) | 密码哈希 |
| avatar_media_id | bigint NULL | 头像媒体ID（关联 media_files.id） |
| role_id | bigint | 角色ID（关联 user_roles.id） |
| status | tinyint | 状态：1-启用，2-禁用 |
| login_failed_count | int | 连续登录失败次数 |
| locked_until | datetime NULL | 锁定截止时间 |
| last_login_at | datetime NULL | 最后登录时间 |
| totp_secret | varchar(255) NULL | 双因素认证密钥（V1.1 启用） |
| created_at / updated_at | timestamp | 创建/更新时间 |

索引：`email` 唯一；`role_id`、`status` 普通。V1.0 单角色（role_id），V1.2 演进为 `user_role_user` 多角色 pivot。

#### 表：user_roles（角色表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 角色ID |
| name | varchar(32) | 角色名称（超级管理员/管理员/编辑/作者/审核员） |
| code | varchar(32) UK | 角色标识（super_admin/admin/editor/author/auditor） |
| description | varchar(255) | 角色说明 |
| is_system | tinyint | 是否内置角色：1-是，2-否 |
| created_at / updated_at | timestamp | 创建/更新时间 |

V1.0 预置 5 条内置数据（种子），权限矩阵见 PRD 2.2 与本文 7.1。

#### 表：password_reset_tokens（密码重置令牌表）

email (PK)、token、created_at，结构同 Laravel 标准。

#### 表：user_audit_logs（操作审计日志表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 日志ID |
| user_id | bigint | 操作人ID |
| action | varchar(64) | 动作标识（如 login、post.publish、user.disable） |
| subject_type | varchar(64) NULL | 操作对象类型（如 Post、Page、MediaFile） |
| subject_id | bigint NULL | 操作对象ID |
| ip | varchar(45) | 操作IP |
| user_agent | varchar(255) | 浏览器标识 |
| detail | json NULL | 详情（变更前后快照等） |
| created_at | timestamp | 操作时间 |

只增不改，保留 ≥ 180 天（PRD FR-505）；按 `user_id + created_at`、`action` 建索引。

#### 枚举类（gen:enum 生成）

`UserStatusEnum`（1-启用，2-禁用）、`RoleIsSystemEnum`（1-是，2-否）

#### 应用服务（app/Services/User）

- `UserAccountService`：邀请建号、启用/禁用、重置密码、角色分配（写审计日志）
- `LoginSecurityService`：失败计数、锁定 15 分钟（FR-503 限流部分）、登录事件审计
- （V1.1）`TotpService`：双因素认证绑定与校验

#### 权限 enforcement

- 路由中间件：`auth` + `role:{code}`（Admin 模块路由组统一施加）；
- Policy：`PostPolicy`（编辑仅管理自己内容、作者仅草稿/待审）、`PagePolicy`、`MediaPolicy`（作者仅删自己上传）、`UserPolicy`（管理员不可管理超级管理员）；
- 越权一律 403 并落 `user_audit_logs`。

### 5.2 Content 域（V1.0 核心）

**职责**：文章与单页的内容生命周期（草稿→审核→发布→下线）、定时发布、置顶推荐、回收站、SEO 字段。对应 PRD 4.2（FR-201~209、221）。

#### 表：content_posts（文章表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 文章ID |
| title | varchar(200) | 标题 |
| slug | varchar(200) UK | URL标识（支持中文转拼音，可配置） |
| excerpt | varchar(500) NULL | 摘要 |
| cover_media_id | bigint NULL | 封面媒体ID（关联 media_files.id） |
| category_id | bigint | 分类ID（关联 taxonomy_categories.id） |
| content | longtext | 正文内容 |
| content_format | tinyint | 内容格式：1-Markdown，2-富文本HTML |
| status | tinyint | 状态：0-草稿，10-待审核，20-已发布，30-定时发布，40-已下线 |
| published_at | datetime NULL | 发布时间 |
| scheduled_at | datetime NULL | 定时发布时间 |
| is_pinned | tinyint | 是否置顶：1-是，2-否 |
| is_recommended | tinyint | 是否推荐：1-是，2-否 |
| source_type | tinyint | 来源类型：1-原创，2-转载 |
| source_url | varchar(500) NULL | 转载原文链接 |
| seo_title | varchar(200) NULL | SEO标题（空则按规则生成） |
| seo_description | varchar(300) NULL | SEO描述 |
| seo_keywords | varchar(200) NULL | SEO关键词 |
| view_count | bigint | 阅读量（冗余计数） |
| comment_count | bigint | 评论数（V1.1 启用维护） |
| created_by / updated_by | bigint | 创建人/最后编辑人ID |
| deleted_at | datetime NULL | 删除时间（回收站，保留30天） |
| created_at / updated_at | timestamp | 创建/更新时间 |

索引：`slug` 唯一；`(category_id, status, published_at)` 列表组合；`(status, published_at)`；`(is_pinned, published_at)` 首页排序；（V1.1）`FULLTEXT(title, excerpt, content) WITH PARSER ngram` 站内搜索。

**设计说明**：

- **回收站 = 软删除**（FR-208）：`deleted_at` 非空即回收站；队列任务每日清理 30 天前记录并硬删除；
- **草稿自动保存**（FR-203）：前台每 30s 调用后台草稿更新接口，直接更新 `status=0` 的同一行，无独立草稿表；
- **定时发布**（FR-204）：`status=30` + `scheduled_at`，调度任务每分钟扫描到期文章交由 `PostManageService::publish()` 发布；
- **正文渲染**：Markdown 源文存库，前台渲染结果写入缓存（见 7.2），不在库内冗余 HTML。

#### 表：content_pages（单页表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 单页ID |
| title | varchar(200) | 标题 |
| slug | varchar(200) UK | URL标识 |
| template | varchar(64) NULL | 模板标识（空用默认 page 模板） |
| content | longtext | 正文内容 |
| content_format | tinyint | 内容格式：1-Markdown，2-富文本HTML |
| status | tinyint | 状态：1-已发布，2-草稿 |
| seo_title / seo_description / seo_keywords | varchar NULL | SEO属性（同文章） |
| created_by / updated_by | bigint | 创建人/最后编辑人ID |
| deleted_at / created_at / updated_at | — | 同文章 |

#### 表：content_post_revisions（文章修订版本表，V1.1）

id、post_id、snapshot json（全属性快照）、created_by、created_at。每次"存为修订/更新发布"生成快照；对比与回滚由应用服务基于快照实现，回滚生成新修订（FR-207）。

#### 枚举类

`PostStatusEnum`（0/10/20/30/40）、`ContentFormatEnum`（1-Markdown，2-富文本HTML）、`PostSourceTypeEnum`（1-原创，2-转载）、`PageStatusEnum`（1-已发布，2-草稿）、通用 `YesNoEnum`（1-是，2-否）

#### 应用服务（app/Services/Content）

- `PostManageService`：创建/更新（含 slug 生成与唯一化）、publish（立即/定时）、offline、pin/recommend、restore、快照修订（V1.1）；发布/下线时发布领域事件 `PostPublished` / `PostUpdated`
- `PageService`：单页 CRUD 与模板绑定
- `RecycleBinService`：回收站列表/恢复/清空（复用 PostManageService）

#### 领域事件订阅（跨领域协同在此层完成）

`PostPublished` → 失效前台缓存（7.2）、刷新 sitemap 缓存（7.5）、（V1.1）触发 Webhook 与统计初始化。

### 5.3 Taxonomy 域（V1.0）

**职责**：分类（≤3 级）与标签管理。对应 PRD 4.2.3（FR-231/232）。

#### 表：taxonomy_categories（分类表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 分类ID |
| parent_id | bigint NULL | 父分类ID（顶级为空，最多三级） |
| name | varchar(64) | 分类名称 |
| slug | varchar(64) UK | URL标识 |
| description | varchar(255) NULL | 分类描述 |
| seo_title / seo_description / seo_keywords | varchar NULL | SEO属性 |
| sort | int | 排序（值小在前） |
| status | tinyint | 状态：1-启用，2-禁用 |
| post_count | int | 文章数（冗余计数） |
| created_at / updated_at | timestamp | 创建/更新时间 |

约束：删除前校验无子分类且有内容的分类必须先移走内容（FR-231）；层级深度校验在 `CategoryService`。

#### 表：taxonomy_tags（标签表）

id、name varchar(64) UK、slug varchar(64) UK、post_count int（引用文章数，冗余）、timestamps。合并标签时迁移关联并累加计数（FR-232）。

#### 表：taxonomy_post_tag（文章标签关联表）

post_id、tag_id，联合唯一 `(post_id, tag_id)`。

#### 枚举类

`CategoryStatusEnum`（1-启用，2-禁用）

#### 应用服务

- `CategoryService`：树形查询（缓存）、层级与环检测、删除前校验
- `TagService`：标签 CRUD、合并（merge）

### 5.4 Media 域（V1.0）

**职责**：媒体上传（GCS）、去重、引用追踪、删除保护。对应 PRD 4.4（FR-401/402/404/405）。

#### 表：media_files（媒体文件表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 媒体ID |
| disk | varchar(32) | 存储盘标识（gcs） |
| path | varchar(500) | 存储相对路径 |
| name | varchar(255) | 原始文件名 |
| extension | varchar(16) | 扩展名 |
| mime_type | varchar(128) | 真实MIME类型 |
| size | bigint | 文件大小（字节） |
| type | tinyint | 类型：1-图片，2-视频，3-音频，4-附件 |
| width / height | int NULL | 图片宽/高（像素） |
| duration | int NULL | 音视频时长（秒） |
| hash | char(40) | 文件SHA1（秒传去重） |
| thumbnails | json NULL | 缩略图规格映射（V1.1 启用） |
| uploaded_by | bigint | 上传人ID |
| created_at | timestamp | 上传时间 |

索引：`hash`（去重命中直接复用记录）、`(type, created_at)`。

#### 表：media_usages（媒体引用表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 引用ID |
| file_id | bigint | 媒体ID |
| subject_type | varchar(64) | 引用主体类型（如 Post、Page、User） |
| subject_id | bigint | 引用主体ID |
| field | varchar(64) | 引用字段（cover/content/avatar） |
| created_at | timestamp | 创建时间 |

索引：`(subject_type, subject_id)`、`file_id`。内容保存时由应用服务扫描正文中的媒体引用并重建（先删后插）；删除媒体时查询本表给出"仍被 N 篇内容引用"警告（FR-404）。

#### 枚举类

`MediaTypeEnum`（1-图片，2-视频，3-音频，4-附件）

#### 应用服务

- `MediaUploadService`：扩展名白名单 + finfo 真实 MIME 校验（FR-405，伪造扩展名拒绝并审计）→ SHA1 秒传查重 → GCS 写入（`gs://{bucket}/media/{Ym}/{hash前2位}/{hash}.{ext}`）→ 入库；（V1.1）异步生成缩略图规格
- `MediaUsageService`：引用扫描与重建、删除前引用检查

> **依赖**：Flysystem 的 GCS 适配器为新增 composer 依赖，实施前需评审（CLAUDE.md 约定，见第 11 章）。

### 5.5 Setting 域（V1.0 部分，V1.1 扩展）

**职责**：站点配置 KV、SEO 全局项、301 重定向与导航菜单（V1.1）。对应 PRD 4.7（FR-701~705）。

#### 表：setting_settings（站点配置表）

| 字段 | 类型 | 注释 |
| --- | --- | --- |
| id | bigint PK | 配置ID |
| group | varchar(32) | 配置分组（site/seo/reading/comment） |
| key | varchar(64) UK | 配置键（如 site.name、seo.title_suffix） |
| value | text | 配置值（JSON） |
| remark | varchar(255) NULL | 配置说明 |
| created_at / updated_at | timestamp | 创建/更新时间 |

配置读写走 `SiteSettingService`（整组缓存，写入失效），后台「系统设置」按分组 Tab 渲染（PRD 附录）。

#### 表：setting_redirects（重定向表，V1.1）

id、from_path varchar(500) UK、to_url varchar(500)、status_code smallint（301/302）、hits int、status tinyint（状态：1-启用，2-禁用）、timestamps。中间件命中即 30x 跳转并累加 hits（FR-704）。

#### 表：setting_nav_menus / setting_nav_menu_items（V1.1）

菜单：id、name、code UK（header/footer）、timestamps。
菜单项：id、menu_id、parent_id NULL、title、target_type tinyint（类型：1-自定义链接，2-分类，3-单页，4-文章）、target_id bigint NULL、url varchar(500) NULL、sort、status tinyint（状态：1-启用，2-禁用）、timestamps。支持拖拽排序（FR-222）。

#### 应用服务

- `SiteSettingService`：分组读写 + 缓存 + 全站生效
- （V1.1）`RedirectService`（含批量导入）、`NavMenuService`（树构建 + 缓存）

### 5.6 Comment 域（V1.1）

**职责**：评论（二级回复）、先审后发/先发后审、敏感词。对应 PRD 4.6（FR-601~603）。

- **comment_comments（评论表）**：id、post_id、user_id、parent_id NULL（仅二级）、content text、status tinyint（状态：1-已通过，2-待审核，3-已驳回）、reject_reason varchar(255) NULL、ip varchar(45)、created_at。索引 `(post_id, status, created_at)`。
- **comment_sensitive_words（敏感词表）**：id、word varchar(64) UK、replacement varchar(64) NULL、status tinyint（状态：1-启用，2-禁用）、timestamps。
- 枚举：`CommentStatusEnum`。
- 应用服务：`CommentService`（发表策略判定、敏感词 DFA 匹配、审核流转、驳回通知）；前台展示仅 `status=1`。
- 回复他人评论 → 站内通知（notifications 表 + Laravel Notification）。

### 5.7 Workflow 域（V1.1）

**职责**：投稿审核流。对应 PRD 4.3（FR-301~303）。

- **workflow_records（审核流转记录表）**：id、post_id、action tinyint（动作：1-提交审核，2-审核通过，3-审核驳回）、operator_id、reason varchar(500) NULL（驳回理由/备注）、created_at。
- 与 `content_posts.status`（10↔20/40）联动；开关在 `setting_settings`（group=reading，key=review_required）。
- 应用服务：`ReviewService`（提交/通过/驳回 + 状态机校验 + 记录 + 通知作者）。

### 5.8 Stat 域（V1.1）

**职责**：轻量自建统计（PV/UV），隐私友好。对应 PRD 4.11（FR-104/1101/1102）。

- **stat_view_logs（访问事件表）**：id、date date、post_id bigint NULL（空为非文章页）、session_hash char(32)（会话+UA 匿名哈希，不存可识别信息）、created_at。仅作当日聚合源，保留 7 天。
- **stat_daily_post_views（文章日统计表）**：id、date、post_id、pv int、uv int，唯一 `(date, post_id)`。
- **stat_daily_site_views（站点日统计表）**：id、date UK、pv int、uv int。
- 应用服务：`ViewTrackService`（前台埋点入队列）、`StatAggregateService`（每日队列聚合 view_logs → 日表，`BITCOUNT` 思路以 distinct session_hash 计 UV）。

### 5.9 System 域（V1.0）

框架基础表，**保留 Laravel 标准命名**（App Engine 部署依赖 database 驱动，见部署指南）：

- `sessions`：SESSION_DRIVER=database
- `cache` / `cache_locks`：CACHE_STORE=database
- `jobs` / `job_batches` / `failed_jobs`：QUEUE_CONNECTION=database
- `notifications`（V1.1）：站内通知

集中在一个迁移文件 `create_system_domain_tables.php`。

---

## 6. 模块与路由设计

### 6.1 Admin 模块（`/admin`，中间件 `auth` + `role`）

| 控制器 | 聚合动作（每个方法需 OA 注解，军规 3.6） |
| --- | --- |
| DashboardController | index（仪表盘，FR-101~103） |
| PostController | index、create、store、edit、update、destroy（入回收站）、restore、forceDelete、publish、offline、pin（FR-201~206/208/209） |
| PageController | index、create、store、edit、update、destroy（FR-221） |
| CategoryController | index、store、update、destroy、sort（FR-231） |
| TagController | index、store、merge、destroy（FR-232） |
| MediaController | index、store、update、destroy（FR-401/404） |
| UserController | index、store、update、toggleStatus、resetPassword（FR-501） |
| RoleController | index（V1.0 内置角色只读） |
| AuditLogController | index（FR-505） |
| SettingController | index、update（FR-701/702） |
| （V1.1）ReviewController | pending、show、approve、reject |
| （V1.1）CommentController | index、approve、reject、reply、destroy |
| （V1.1）RedirectController / MenuController / StatController | 略 |

路由入口示例（`app/Modules/Admin/Routes/route.php`）：

```php
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'role:admin,super_admin,editor,author,auditor'])
    ->group(function () {
        require __DIR__.'/route.gen.php';
    });
```

### 6.2 Portal 模块（`/`，前台）

| 控制器 | 聚合动作 |
| --- | --- |
| PostController | home（首页）、search（列表/搜索，category/tag/关键词参数）、show（详情） |
| PageController | show（单页） |
| SeoController | sitemap、robots（FR-703） |
| （V1.1）CommentController | store、reply（读者评论） |

### 6.3 User 模块（V1.1，`/user`，登录读者）

ProfileController（edit/update）、NotificationController（index/read）。

### 6.4 Api 模块（V1.2，`/api/v1`，Sanctum Token + 限流）

PostController（search/show）、CategoryController（index）、TagController（index）。严格 DTO（军规 3.4），导出 openapi.json 供第三方。

---

## 7. 横切设计

### 7.1 认证与授权（RBAC）

- 认证：Laravel session guard（`user_users` 表）；登录限流 5 次失败锁 15 分钟（FR-503）；
- 授权：内置 5 角色对应 PRD 2.2 权限矩阵，落地为 `role` 中间件（粗粒度路由级）+ Policy（细粒度数据级，见 5.1）；
- 审计：所有写操作经 `UserAccountService`/各应用服务统一落 `user_audit_logs`。

### 7.2 缓存策略（只读文件系统约束）

- 驱动：`CACHE_STORE=database`（无 tags 支持 → **显式 key 管理代替标签失效**）；
- Key 规范：`hugecms:{module}:{entity}:{id或分页标识}`，如 `hugecms:portal:post:42`、`hugecms:portal:post:list:{category}:{page}:{queryHash}`；
- 失效：`PostPublished`/`PostUpdated`/`PostOffline` 事件订阅器内按 key 规则主动删除（列表 key 含分页哈希，失效时用 `Cache::flush` 于前缀桶：database 驱动按 key 前缀遍历删除，列表页接受短 TTL（5 分钟）兜底）；
- 正文渲染缓存：Markdown→HTML 结果缓存，同 key 失效；
- 后台一律不走页面缓存。

### 7.3 队列与调度

队列（database 驱动）+ 独立 worker 服务部署（见 [部署指南](../deployments/appengine-standard.md) 第 9 节）：

| 任务 | 触发 | 说明 |
| --- | --- | --- |
| PruneScheduledPosts | 调度每分钟 | 到期定时文章发布（FR-204，误差≤1分钟） |
| PruneRecycleBinJob | 调度每日 | 清理回收站 30 天记录（FR-208） |
| RefreshSitemapJob | 事件 + 调度兜底 | sitemap 重建（FR-703） |
| （V1.1）AggregateDailyStatsJob | 调度每小时 | stat_view_logs → 日表聚合 |
| （V1.1）GenerateThumbnailsJob | 上传事件 | 缩略图生成（FR-403） |
| （V1.1）PruneViewLogsJob | 调度每日 | 清理 7 天前事件日志 |

### 7.4 媒体存储（GCS）

Flysystem GCS 适配器 + `disk('gcs')`；URL 支持 CDN 域名前缀（setting：site.cdn_url）；上传安全链路见 5.4。编辑器粘贴/拖拽图片走同一上传接口。

### 7.5 SEO 实现

- TDK 生成链：文章自定义值 > 分类覆盖 > 全局规则（`{标题} - {站点名}` 等，setting:seo.*）；
- sitemap：`SeoController@sitemap` 输出，内容缓存于 database cache，事件驱动刷新；
- 结构化数据（V1.1）：Blade 局部视图输出 Article/BreadcrumbList JSON-LD（FR-705）；
- 301 中间件（V1.1）：全局限先于路由匹配查 `setting_redirects`。

### 7.6 日志与错误处理

- 应用日志：`LOG_CHANNEL=stderr` → Cloud Logging；结构化 context（user_id、route、request_id）；
- 错误页：403/404/500 模块化 Blade 页面；生产环境 APP_DEBUG=false；
- 审计与日志分离：业务审计进 `user_audit_logs`，技术日志进 stderr。

### 7.7 API 与响应契约

- Web 后台的 JSON 端点（上传、批量操作）统一响应结构 `{code, message, data}`，配套 Response DTO；
- Api 模块（V1.2）：版本化 `/api/v1`、Sanctum Token、限流（60 次/分钟/Token）、分页契约（page/per_page/total）；
- openapi.json 由 `zircot/swagger-php` 扫描注解生成（依赖评审见第 11 章）。

---

## 8. 前端设计

| 端 | 技术方案 | 说明 |
| --- | --- | --- |
| Admin | Blade（`admin::` 命名空间）+ Tailwind 4 + 轻量原生 JS | 不引入重型前端框架；列表/表单/上传为组件化 Blade 局部视图 |
| 编辑器 | 候选 Tiptap（富文本 + Markdown 双模式）/ CodeMirror 6（Markdown 源码模式） | **第 1 周内 Spike 定版**（PRD 风险表首位）；粘贴上传、自动保存（30s）、字数统计 |
| Portal 主题 | Blade（`portal::` 命名空间）+ Tailwind 4 | V1.0 单一默认主题；模板：layouts/、home、list、show、page、search；Lighthouse 移动端 ≥ 90（FR-901）；V1.2 子主题覆盖机制 |
| 构建产物 | `npm run build` → `public/build` 随部署上传 | App Engine 不跑 npm（部署指南） |

---

## 9. 测试策略

- 框架：Pest（仓库既有约定），工厂随模型生成配套；
- **权限矩阵测试（必须）**：PRD 2.2 每格至少一条用例（角色 × 功能域），越权断言 403 + 审计落库；
- 内容生命周期测试：草稿自动保存、定时发布（时间伪造）、下线即前台不可见（缓存失效断言）、回收站 30 天清理；
- 上传安全测试：伪造扩展名拒绝、白名单外类型拒绝、引用保护；
- 缓存失效测试：发布/编辑/下线后前台取新值；
- 跑法：`php artisan test --compact`（窄集）→ 全量由用户执行；交付前执行军规 3.7 收尾三部曲。

---

## 10. 部署与运行约束（摘要）

详见 [部署指南](../deployments/appengine-standard.md)，与设计直接相关的约束：

1. 只读文件系统：所有缓存/会话/队列走 database；上传走 GCS；日志走 stderr——**任何新依赖选型必须审查其写盘行为**；
2. 定时发布依赖调度：worker 服务（basic_scaling）常驻 `queue:work` + `schedule:run`；
3. 迁移经 Cloud SQL Auth Proxy 在本地执行；
4. 每次部署前 `npm run build` 并确保 `public/build` 已上传。

---

## 11. 依赖引入清单与风险（实施前评审）

| 项 | 用途 | 风险/备注 |
| --- | --- | --- |
| DevTools 代码生成工具链（`gen:xxx`/`gen:route`/`gen:enum`） | 军规 3.1/3.2/3.6 的执行载体 | 团队自有工具，需 composer 接入并适配 Laravel 13；**MVP 前置任务** |
| Flysystem GCS 适配器 | 媒体云存储 | 需评审选型（候选 superbalist 系或 Laravel 官方适配包）；只读 FS 兼容性验证 |
| zircot/swagger-php | OA 注解与 openapi.json | Api 模块（V1.2）与 gen:route（3.6）共同依赖，MVP 即引入 |
| 编辑器（Tiptap/CodeMirror） | 内容编辑 | 第 1 周 Spike 定版（PRD 风险表） |
| Laravel Sanctum | Api Token | V1.2 引入即可 |

## 12. 附录：需求追踪矩阵（FR → 技术落点）

| PRD 模块 | FR 区间 | 技术落点 |
| --- | --- | --- |
| 仪表盘 | FR-101~104 | Admin\DashboardController + Stat 域（104） |
| 文章/单页 | FR-201~209/221/222 | Content 域 + Admin\PostController/PageController + PostManageService |
| 分类/标签 | FR-231/232 | Taxonomy 域 + CategoryService/TagService |
| 审核工作流 | FR-301~303 | Workflow 域 + ReviewService（V1.1） |
| 媒体库 | FR-401~405 | Media 域 + MediaUploadService + GCS |
| 用户与权限 | FR-501~505 | User 域 + RBAC（7.1）+ 审计 |
| 评论 | FR-601~603 | Comment 域（V1.1） |
| SEO/设置 | FR-701~705 | Setting 域 + SeoController + TDK 生成链（7.5） |
| 搜索 | FR-801/802 | content_posts FULLTEXT(ngram) + Portal\PostController@search（V1.1） |
| 主题前台 | FR-901~904 | Portal 模块 Views + 默认主题（V1.2 子主题） |
| 多语言 | FR-1001/1002 | 后台 lang 包（V1.1）；内容多语言单独立项（V1.2） |
| 统计 | FR-104/1101~1103 | Stat 域 + ViewTrackService（V1.1） |
| 开放 API | FR-1201/1202 | Api 模块 + Sanctum + Webhook（V1.2） |
| 系统维护 | FR-1301~1303 | 缓存管理（SettingController）+ 备份任务 + 状态页（V1.1） |

---

**下一步建议**：① 评审本文档与 PRD 第 10 节假设；② 确认 DevTools 工具链接入方式；③ 编辑器 Spike（1 周内）；④ 输出 V1.0 迭代 backlog（以领域为交付单元：User → Taxonomy → Content → Media → Setting → Admin/Portal 模块）。
