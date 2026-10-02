# HugeCMS 部署指南：Google App Engine 标准环境（PHP 标准运行时）

> 适用版本：Laravel 13.34 / PHP 8.4（`composer.json` 要求 `^8.3`，与 `php84` 运行时兼容）
> 文档日期：2026-10-02
> 部署目标：App Engine 标准环境（第 2 代 PHP 运行时）+ Cloud SQL（MySQL 8.4）

## 一、核心约束（先理解这 3 点）

App Engine 标准环境与普通虚拟机不同，Laravel 的部署配置都围绕以下约束展开：

1. **运行时文件系统只读**，只有 `/tmp` 可写（内存盘）
   → Blade 编译视图、缓存、session、日志都必须绕开 `storage/` 目录。
   本项目通过环境变量解决，**无需修改任何 PHP 代码**：
   - `VIEW_COMPILED_PATH=/tmp/laravel-views`（框架自动创建目录）
   - `LOG_CHANNEL=stderr`（日志写入 stderr，直接进入 Cloud Logging）
   - `SESSION_DRIVER` / `CACHE_STORE` / `QUEUE_CONNECTION` 均为 `database`（与 `.env.example` 默认值一致）

2. **构建时只执行 `composer install`，不执行 npm**
   → Vite 前端产物必须在本地构建（`npm run build`），并随代码一起上传（`public/build/` 目录）。

3. **MySQL 必须使用 Cloud SQL + Unix socket 连接**，不能用 TCP
   → 通过 `app.yaml` 的 `beta_settings.cloud_sql_instances` 挂载 `/cloudsql/<连接名>` socket，
   数据库连接使用 `DB_SOCKET` 而非 `DB_HOST`。

其他有利条件：

- Laravel 的 `public/index.php` 会被运行时**自动识别为前端控制器**，无需 nginx 配置；
  `public/` 下真实存在的静态文件（CSS/JS/字体等）由运行时直接服务。
- [config/database.php](../config/database.php) 已支持 `DB_SOCKET`（`unix_socket`）。
- [config/logging.php](../config/logging.php) 已内置 `stderr` 日志通道。

## 二、参数速查表

部署前先确定以下参数（下文以占位符出现）：

| 占位符 | 含义 | 获取方式 |
| --- | --- | --- |
| `PROJECT_ID` | GCP 项目 ID | `gcloud config get-value project` |
| `REGION` | Cloud SQL 实例区域 | 建议与 App Engine 应用同区域，如 `asia-east1` |
| `INSTANCE_NAME` | Cloud SQL 实例名 | 本文统一用 `hugecms-db` |
| `INSTANCE_CONNECTION_NAME` | 实例连接名 | 格式 `PROJECT_ID:REGION:INSTANCE_NAME`，见下方命令 |
| `DB_PASSWORD` | Cloud SQL 数据库用户密码 | 自行设定并妥善保管 |

```bash
# 查询实例连接名
gcloud sql instances describe hugecms-db --format='value(connectionName)'
```

> ⚠️ `app.yaml` 中包含 `APP_KEY` 和数据库密码。若仓库多人共享，建议将根目录的 `app.yaml`
> 加入 `.gitignore` 单独管理，仅在部署时放置于项目根目录。

## 三、部署文件（放在项目根目录）

### 1. `app.yaml`

```yaml
runtime: php84
instance_class: F2          # F1 内存 256M 跑 Laravel 偏紧，建议 F2 起步

env_variables:
  APP_NAME: HugeCMS
  APP_ENV: production
  APP_DEBUG: false
  APP_URL: https://PROJECT_ID.appspot.com
  APP_KEY: base64:xxxx       # 本地执行 php artisan key:generate --show 生成后填入

  LOG_CHANNEL: stderr        # 日志写 stderr，直接进 Cloud Logging
  LOG_LEVEL: info

  DB_CONNECTION: mysql
  DB_DATABASE: hugecms
  DB_USERNAME: hugecms
  DB_PASSWORD: DB_PASSWORD
  DB_SOCKET: /cloudsql/INSTANCE_CONNECTION_NAME

  SESSION_DRIVER: database   # 以下三项避免写本地文件（只读文件系统）
  CACHE_STORE: database
  QUEUE_CONNECTION: database

  VIEW_COMPILED_PATH: /tmp/laravel-views   # Blade 编译产物写到 /tmp

beta_settings:
  cloud_sql_instances: INSTANCE_CONNECTION_NAME   # 挂载 /cloudsql socket
```

### 2. `.gcloudignore`

避免上传无关文件、让依赖在 Google 侧构建：

```
.gcloudignore
.git
node_modules
vendor
.env
.env.*
!.env.example
tests
database/database.sqlite
storage/logs/*
storage/framework/*
.vscode
.idea
```

**注意：**

- **不要**忽略 `composer.lock` —— 保证云端构建依赖版本一致；
- **不要**忽略 `public/build/` —— 前端构建产物，App Engine 构建时不跑 npm；
- 忽略 `vendor` 后，`composer install` 会在部署构建时于 Google 侧执行。

## 四、一次性云端准备

```bash
# 1. 安装 gcloud CLI（Windows 安装器：https://cloud.google.com/sdk/docs/install）
gcloud auth login
gcloud config set project PROJECT_ID

# 2. 创建 App Engine 应用
#    ⚠️ 区域创建后不可更改；面向国内用户建议 asia-east1（台湾）或 asia-east2（香港）
gcloud app create --region=asia-east1

# 3. 启用 API + 创建 Cloud SQL（MySQL 8.4）
gcloud services enable appengine.googleapis.com sqladmin.googleapis.com
gcloud sql instances create hugecms-db \
  --database-version=MYSQL_8_4 --tier=db-f1-micro --region=asia-east1
gcloud sql databases create hugecms --instance=hugecms-db
gcloud sql users create hugecms --instance=hugecms-db --password=DB_PASSWORD

# 4. 授权 App Engine 默认服务账号访问 Cloud SQL
gcloud projects add-iam-policy-binding PROJECT_ID \
  --member=serviceAccount:PROJECT_ID@appspot.gserviceaccount.com \
  --role=roles/cloudsql.client
```

完成后，将实例连接名（`PROJECT_ID:REGION:hugecms-db`）回填到 `app.yaml` 的
`DB_SOCKET` 与 `beta_settings.cloud_sql_instances` 两处。

## 五、构建并部署

```bash
# 1. 本地构建前端产物（确保 public/build 为最新）
npm install && npm run build

# 2. 部署（composer install 在 Google 侧构建时执行）
gcloud app deploy
# 若提示缺少组件：gcloud components install app-engine-php

# 3. 打开线上地址
gcloud app browse    # https://PROJECT_ID.appspot.com
```

## 六、执行数据库迁移

App Engine 不允许 SSH，标准做法是本地通过 Cloud SQL Auth Proxy 连接生产库执行迁移：

```bash
# 1. 下载并启动代理（Windows）
#    https://github.com/GoogleCloudPlatform/cloud-sql-proxy/releases
cloud-sql-proxy.exe INSTANCE_CONNECTION_NAME --port 3306

# 2. 另开一个终端：本地 .env 中 DB_HOST=127.0.0.1、DB_PORT=3306，
#    账号密码使用 Cloud SQL 的 hugecms 用户
php artisan migrate --force
```

## 七、部署后验证清单

- [ ] 首页正常打开，无 500 错误
- [ ] `https://PROJECT_ID.appspot.com/up` 健康检查返回 200
- [ ] CSS/JS 正常加载（`/build/assets/...` 静态文件由运行时直接服务）
- [ ] 登录/表单提交正常（验证 database session 生效）
- [ ] `gcloud app logs tail -s default` 能看到 Laravel 的 stderr 日志流
- [ ] 数据库写入正常（Cloud SQL 连接无误）

## 八、故障排查

| 现象 | 原因与处理 |
| --- | --- |
| 500，日志中出现 `file_put_contents(...storage/...) failed` | 有组件尝试写只读目录。检查 `VIEW_COMPILED_PATH`、`LOG_CHANNEL`、`SESSION_DRIVER`、`CACHE_STORE` 是否按上文设置 |
| 数据库连接失败 `SQLSTATE[HY000] [2002]` | `DB_SOCKET` 未生效或实例未授权。确认 `beta_settings.cloud_sql_instances` 已配置、服务账号已绑定 `roles/cloudsql.client`、连接名格式正确 |
| 静态资源 404 | 确认 `public/build` 已上传（`.gcloudignore` 未误排除）；必要时在 `app.yaml` 中为 `public/` 下静态目录显式添加 `handlers` |
| `No application encryption key` | `APP_KEY` 未写入 `app.yaml` 的 `env_variables` |
| 页面报缺少扩展（如 redis/memcached） | 在项目根目录新增 `php.ini`，按需添加 `extension=redis` 等 |
| 首次访问慢 1–2 秒 | 实例冷启动（自动扩缩到 0）。介意延迟可设 `min_instances: 1`（产生持续费用） |
| 部署时构建失败提示 PHP 版本不匹配 | `composer.json` 的 `php` 约束需与 `runtime: php84` 兼容（当前 `^8.3`，满足） |

## 九、可选进阶

### 队列 worker（独立服务）

App Engine Web 服务不支持常驻进程。新增根目录文件 `app-worker.yaml`：

```yaml
service: worker
runtime: php84
instance_class: B2
entrypoint: php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
basic_scaling:
  max_instances: 1
# env_variables / beta_settings 与 app.yaml 相同
```

从项目根目录部署：`gcloud app deploy app-worker.yaml`。

### 任务调度

两种方式任选：

- `cron.yaml` 每分钟请求一个受保护的 Laravel 路由，路由内执行 `Artisan::call('schedule:run')`；
- 在 worker 服务入口改跑 `php artisan schedule:work`。

### 文件上传（CMS 必读）

标准环境本地磁盘不可写，**用户上传必须改用 Cloud Storage**（通过 Flysystem 的
Google Cloud Storage 适配器）。涉及新增 composer 依赖，实施前需团队确认。

## 十、参考链接

- [PHP runtime environment | App Engine standard environment](https://cloud.google.com/appengine/docs/standard/php-gen2/runtime)
- [Connect from App Engine standard environment | Cloud SQL](https://cloud.google.com/sql/docs/mysql/connect-app-engine-standard)
- [Create a socket connection by using PHP | Cloud SQL for MySQL](https://cloud.google.com/sql/docs/mysql/samples/cloud-sql-mysql-pdo-connect-unix)
- [Storing and serving static files | App Engine standard environment](https://cloud.google.com/appengine/docs/standard/serving-static-files)
- [Cloud SQL Auth Proxy](https://cloud.google.com/sql/docs/mysql/sql-proxy)
- [Introducing PHP 7.2 runtime on App Engine standard environment（前端控制器机制）](https://cloud.google.com/blog/products/application-development/introducing-php-7-2-runtime-on-the-app-engine-standard-environment)
