# ECBoot Admin 前端基础设施设计

- 日期：2026-10-20
- 状态：待审阅
- 范围：`packages/ecboot-admin`

## 1. 背景与目标

ecboot-admin 是 ECBoot B2C 自营电商管理后台的前端（Vue 3.5 + Vite 8 + TypeScript + Element Plus 2.14 + Pinia 4 + Vue Router 5）。目前仅有 mock 登录页，缺少所有后续页面都将依赖的基础设施。本设计覆盖：

1. axios 封装与拦截器，对接后端 `{ code, message, data }` 响应约定
2. 路由表、全局登录守卫、NProgress 页面进度条
3. 用户 store：token + 用户信息，localStorage 持久化
4. 后台框架页 Layout + 仪表盘占位页（完整演示「未登录 → 跳登录 → 登录后回跳」流程）
5. 环境变量与 Vite 开发代理

## 2. 已确认的决策

| 决策项 | 结论 |
|--------|------|
| 架构风格 | 方案 A：分层服务式（`api/` `utils/` `stores/` `router/` `layout/`） |
| 范围 | 包含 Layout + Dashboard 占位页 |
| 登录联调 | API 层就绪即可，不做 mock；后端未就绪时登录报错属于预期行为 |
| 通用类型位置 | `src/types/api.ts`（用户指定，不放 `api/types.ts`） |
| Token 存储 | pinia-plugin-persistedstate → localStorage |
| Token 传递 | `Authorization: Bearer <token>` 请求头 |
| 后端响应约定 | `{ code: number, message: string, data: T }`，`code === 0` 成功（`vendor/phpkg/laravel-foundation` `JsonResponses`）；Admin API 前缀 `/api/admin/*` |

## 3. 目录结构

```
packages/ecboot-admin/src/
├── api/
│   └── auth.ts             # login / logout / getProfile，类型从 @/types/api 引入
├── types/
│   └── api.ts              # ApiResponse<T>、UserInfo、LoginParams、LoginResult
├── utils/
│   └── request.ts          # axios 实例 + 请求/响应拦截器
├── stores/
│   ├── index.ts            # createPinia + persist 插件
│   └── user.ts             # token + userInfo + login/logout actions（persist）
├── router/
│   ├── index.ts            # 路由表：login / Layout+dashboard / 404
│   └── guard.ts            # setupRouterGuard()：守卫 + NProgress + document.title
├── layout/
│   ├── index.vue           # el-container 三段式框架页
│   └── components/
│       ├── SidebarMenu.vue # 静态侧边菜单（仪表盘）
│       └── AppHeader.vue   # 顶栏：用户名下拉（退出登录）
├── views/
│   ├── LoginView.vue       # 已有，改造为对接真实 API
│   ├── DashboardView.vue   # 占位页（欢迎卡片）
│   └── NotFoundView.vue    # 404 占位
├── stores/counter.ts       # 删除（脚手架残留）
├── main.ts                 # 启用 persist 插件 + 注册守卫
└── （仓库根：packages/ecboot-admin/.env.development / .env.production）
```

## 4. 模块设计

### 4.1 类型（`src/types/api.ts`）

```ts
// 后端统一响应信封（JsonResponses 约定）
export interface ApiResponse<T = unknown> {
  code: number
  message: string
  data: T
}

export interface UserInfo {
  id: number
  username: string
  nickname: string
}

export interface LoginParams {
  username: string
  password: string
}

// 待定项：后端登录接口未实现，按 Laravel JWT 惯例假定；
// 联调如有出入只需改本文件与 api/auth.ts
export interface LoginResult {
  access_token: string
}
```

### 4.2 axios 封装（`src/utils/request.ts`）

- 实例：`baseURL = import.meta.env.VITE_API_BASE_URL`，`timeout: 10_000`
- 请求拦截器：从 user store 读 token；存在则注入 `Authorization: Bearer <token>`
- 响应拦截器：
  - HTTP 成功（2xx）且 `code === 0` → 解包返回 `data`，调用方签名 `request<T>(): Promise<T>`
  - `code !== 0` → `ElMessage.error(message)` 后 reject
  - `code === 401`（或 HTTP 401）→ 清空登录态，跳转 `/login?redirect=<当前路径>`；若已在登录页则不重复跳转、不重复弹错
  - HTTP 层错误：401 同上；其余（网络错误/超时/5xx）按类别给统一中文文案
- 导出：命名导出实例与 `get/post/put/delete` 快捷方法（api 层直接使用）

### 4.3 用户 store（`src/stores/user.ts`）

- state：`token: string`、`userInfo: UserInfo | null`
- actions：
  - `login(params: LoginParams)`：调 `api/auth.ts` → 存 `token` / `userInfo` → 返回结果供调用方跳转
  - `logout()`：调登出接口（失败不阻塞本地清理）→ 清空 state
- `persist: true`（localStorage，key 为 store id `user`）
- store 不耦合 router：登出后的跳转由调用方（AppHeader、401 拦截）负责

### 4.4 路由与守卫（`src/router/`）

路由表：

| 路径 | 组件 | 说明 |
|------|------|------|
| `/login` | LoginView | 白名单，`meta: { title: '登录' }` |
| `/` | Layout | redirect → `/dashboard`，`meta: { title: '首页' }` |
| `/dashboard`（Layout 子路由） | DashboardView | `meta: { title: '仪表盘' }` |
| `/:pathMatch(.*)*` | NotFoundView | `meta: { title: '404' }` |

`guard.ts` 导出 `setupRouterGuard(router: Router): void`，在 `main.ts` 中 `app.use(router)` 前调用：

- `beforeEach`：
  1. `NProgress.start()`
  2. 白名单（`/login`）放行
  3. 无 token → `redirect /login?redirect=to.fullPath`（避免登录页死循环）
  4. 有 token 且访问 `/login` → `redirect /dashboard`
  5. `document.title = `${to.meta.title} - ECBoot``
- `afterEach`：`NProgress.done()`
- `nprogress/nprogress.css` 在 `guard.ts` 引入

### 4.5 Layout 与页面

- `layout/index.vue`：`el-container`：`el-aside`（SidebarMenu）+ `el-container`（`el-header` AppHeader + `el-main` 内 `router-view`）
- `SidebarMenu.vue`：静态 `el-menu`，当前仅「仪表盘」一项，router 模式
- `AppHeader.vue`：右侧用户名下拉（`el-dropdown`）：退出登录 → `userStore.logout()` → `router.replace('/login')`
- `DashboardView.vue`：欢迎卡片（读取 store 中用户昵称/用户名）
- `LoginView.vue` 改造：移除 mock 延时与 TODO；`handleLogin` 调 `userStore.login()`；成功后 `router.replace(route.query.redirect || '/')`；「记住用户名」逻辑保留

### 4.6 环境变量与代理

- `.env.development`：`VITE_API_BASE_URL=/api`；`vite.config.ts` 增加 `server.proxy`：`'/api'` → `http://127.0.0.1:8000`（`php artisan serve`），`changeOrigin: true`
- `.env.production`：`VITE_API_BASE_URL=/api`（生产由 Nginx 反代到后端，如需改只需动此文件）

## 5. 测试策略

vitest 单测（`@vue/test-utils` + jsdom 已就位）：

1. `utils/request.ts`：响应拦截器「code=0 解包 / code≠0 reject 并提示 / 401 清态跳转」——用自定义 axios adapter 返回构造的信封数据，不发起真实网络请求
2. `stores/user.ts`：login 成功/失败状态流转、logout 清空（pinia 测试用 `createPinia()` + `setActivePinia()`）
3. `router/guard.ts`：`createMemoryHistory` 实例化 router 验证「无 token 访问 /dashboard → 重定向 /login?redirect=...」「有 token 访问 /login → /dashboard」

## 6. 非目标（明确不做）

- 后端菜单驱动的动态路由注册（等后端权限接口就绪后另行设计）
- 按钮级权限、权限指令
- mock 数据层
- 多标签页、主题切换、国际化

## 7. 待定项

| 事项 | 当前假定 | 影响 |
|------|----------|------|
| 登录响应字段 | `data: { access_token: string }` | 联调有出入时只改 `types/api.ts`、`api/auth.ts`、`stores/user.ts` |
| 用户信息获取接口 | 假定 `GET /api/admin/auth/profile` 返回 `UserInfo`；登录接口若直接返回用户信息则省略 getProfile | 仅影响 `api/auth.ts` |
| 401 业务码 | 后端错误码枚举未定义，暂以 `code === 401` 判定 | 集中在 `utils/request.ts` 一处 |
