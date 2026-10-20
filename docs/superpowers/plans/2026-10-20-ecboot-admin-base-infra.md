# ECBoot Admin 前端基础设施实现计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 为 ecboot-admin 搭建 axios 封装与拦截器、路由守卫 + NProgress、用户 store（持久化）、Layout 框架页与登录对接，形成可演示的完整登录闭环。

**Architecture:** 分层服务式（方案 A）：`api/`（接口定义）→ `utils/request.ts`（axios 实例 + 拦截器，解包后端 `{ code, message, data }` 信封）→ `stores/user.ts`（setup 风格 pinia store，persist 到 localStorage）→ `router/guard.ts`（登录守卫 + NProgress）。401 跳转用 `window.location.href`（不 import router，避免循环依赖并彻底重置应用状态）。

**Tech Stack:** Vue 3.5 + TypeScript + Vite 8 + Pinia 4 + pinia-plugin-persistedstate 4 + Vue Router 5 + axios 1.20 + Element Plus 2.14（组件测试真实渲染）+ NProgress 0.2 + vitest 4 (jsdom)

**Spec:** `docs/superpowers/specs/2026-10-20-ecboot-admin-base-infra-design.md`（本计划依据该 spec 实现，执行者需一并阅读）

## Global Constraints

- 所有命令在 `packages/ecboot-admin` 目录下执行（git 仓库根为 `D:\code\git\phpcms`）
- 包管理器：pnpm；测试命令必须带 `run`（`pnpm exec vitest run <path>`，避免进入 watch 模式）
- 跟随现有代码惯例：Vue API **显式导入**（`import { ref } from 'vue'`，不依赖 auto-import）；store 用 **setup 风格**（同 `src/stores/counter.ts`）；Element Plus 组件/图标显式 import（同 `src/views/LoginView.vue`）
- 路径别名统一用 `@/*`（tsconfig 与 vite 均已配置）
- 后端响应约定：`{ code: number, message: string, data: T }`，`code === 0` 成功；Admin API 路径前缀 `/admin/*`（baseURL 已含 `/api`）
- 测试文件统一放 `src/__tests__/*.spec.ts`；element-plus 组件在测试中真实渲染（现有 `App.spec.ts` 已验证可行），不整体 mock element-plus
- UI 文案一律中文；commit message 用中文 conventional 格式（如 `feat(ecboot-admin): ...`）
- 唯一允许的新增依赖：Task 5 的 `@types/nprogress`（devDependency，nprogress 无内置类型，spec 已确定使用 nprogress）
- 每个任务收尾必须：该任务测试 PASS + `pnpm type-check` 通过 + 提交
- 401 判定：业务码 `code === 401` 或 HTTP `status === 401`，集中在 `utils/request.ts`

## Review Focus

以下是 spec 隐含但容易被忽略的输入/场景，最可能影响真实使用者，每项已在对应任务落了测试或人工冒烟步骤：

1. **后端未启动时登录** → 应弹「网络异常，请检查网络连接」类文案，而非未处理的 Promise 异常（Task 3 网络错误分类用例；Task 8 人工冒烟）
2. **token 过期后在受保护页发起请求** → 应清空登录态并跳 `/login?redirect=<当前页>`，登录成功后回跳原页（Task 3 的 401 用例 + Task 7 的 redirect 回跳用例）
3. **未登录直接输入不存在的深层路径（如 `/foo`）** → 应先跳登录页（守卫先于 404 路由生效），而不是先看到 404（Task 5 守卫用例覆盖）
4. **已登录状态手动访问 `/login`** → 应被送回仪表盘，不能停在登录页（Task 5 守卫用例覆盖）
5. **登录按钮双击/重复提交** → loading 期间按钮禁用、不重复发请求（`LoginView` 已有 `:loading` 绑定；Task 8 人工冒烟验证）

---

### Task 1: API 类型定义、环境变量与 Vite 代理

**Files:**
- Create: `.env.development`
- Create: `.env.production`
- Create: `src/types/api.ts`
- Modify: `env.d.ts`
- Modify: `vite.config.ts`

**Interfaces:**
- Consumes: 无（基础声明）
- Produces: `ApiResponse<T>`、`UserInfo`、`LoginParams`、`LoginResult`（后续所有任务从 `@/types/api` 引入）；`import.meta.env.VITE_API_BASE_URL`（Task 3 使用）

- [ ] **Step 1: 创建环境变量文件**

`.env.development`:

```bash
# 开发环境：Vite dev server 代理到本地 Laravel 后端
VITE_API_BASE_URL=/api
```

`.env.production`:

```bash
# 生产环境：由 Nginx 将 /api 反向代理到后端
VITE_API_BASE_URL=/api
```

- [ ] **Step 2: 补充 `env.d.ts` 的环境变量类型**

`env.d.ts` 全量替换为：

```ts
/// <reference types="vite/client" />

interface ImportMetaEnv {
  /** 后端 API 基础路径，如 /api */
  readonly VITE_API_BASE_URL: string
}

interface ImportMeta {
  readonly env: ImportMetaEnv
}
```

- [ ] **Step 3: 创建 `src/types/api.ts`**

```ts
/** 后端统一响应信封（laravel-foundation JsonResponses 约定，code === 0 表示成功） */
export interface ApiResponse<T = unknown> {
  code: number
  message: string
  data: T
}

/** 管理员用户信息 */
export interface UserInfo {
  id: number
  username: string
  nickname: string
}

/** 登录参数 */
export interface LoginParams {
  username: string
  password: string
}

/**
 * 登录结果。
 * 待定项：后端登录接口未实现，按 Laravel JWT 惯例假定字段名；
 * 联调如有出入只需改本文件、api/auth.ts 与 stores/user.ts。
 */
export interface LoginResult {
  access_token: string
}
```

- [ ] **Step 4: `vite.config.ts` 增加开发代理**

在 `defineConfig` 对象内（`plugins` 之后）加入 `server` 配置：

```ts
export default defineConfig({
  plugins: [
    // ...现有 plugins 保持不变
  ],
  server: {
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:8000',
        changeOrigin: true,
      },
    },
  },
  resolve: {
    // ...现有 resolve 保持不变
  },
})
```

- [ ] **Step 5: 验证类型检查通过**

Run: `pnpm type-check`
Expected: 无错误退出（exit 0）

- [ ] **Step 6: Commit**

```bash
git add .env.development .env.production env.d.ts src/types/api.ts vite.config.ts
git commit -m "feat(ecboot-admin): 添加 API 类型定义与环境变量配置"
```

---

### Task 2: 用户 store（最小版）与 pinia 持久化接线

**Files:**
- Create: `src/stores/index.ts`
- Create: `src/stores/user.ts`
- Modify: `src/main.ts`
- Modify: `src/__tests__/App.spec.ts`（修正与当前 App.vue 脱节的既有失败断言）
- Delete: `src/stores/counter.ts`
- Test: `src/__tests__/user.spec.ts`

**Interfaces:**
- Consumes: `UserInfo`（from `@/types/api`）
- Produces: `useUserStore()`（setup 风格），暴露 `token: Ref<string>`、`userInfo: Ref<UserInfo | null>`、`isLoggedIn: ComputedRef<boolean>`、`nickname: ComputedRef<string>`、`reset(): void`；`pinia`（已装 persist 插件的实例，main.ts 与 Task 5 测试使用）。Task 4 将向本 store 增加 `login/logout` actions

- [ ] **Step 1: 写失败测试 `src/__tests__/user.spec.ts`**

```ts
import { beforeEach, describe, expect, it } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useUserStore } from '@/stores/user'

describe('user store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('初始状态：无 token、无用户信息、未登录', () => {
    const userStore = useUserStore()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
    expect(userStore.nickname).toBe('')
  })

  it('有 token 时 isLoggedIn 为 true，nickname 优先取昵称', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin', nickname: '管理员' }

    expect(userStore.isLoggedIn).toBe(true)
    expect(userStore.nickname).toBe('管理员')
  })

  it('userInfo 为空时 nickname 回退为空字符串', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'

    expect(userStore.nickname).toBe('')
  })

  it('reset 清空登录态', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin', nickname: '管理员' }
    userStore.reset()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
  })
})
```

- [ ] **Step 2: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/user.spec.ts`
Expected: FAIL，报错 `Cannot find module '@/stores/user'` 或等效解析错误

- [ ] **Step 3: 创建 `src/stores/index.ts`**

```ts
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

export const pinia = createPinia()

pinia.use(piniaPluginPersistedstate)
```

- [ ] **Step 4: 创建 `src/stores/user.ts`（最小版，login/logout 在 Task 4 加入）**

```ts
import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import type { UserInfo } from '@/types/api'

/** 登录用户状态：token 与用户信息持久化到 localStorage */
export const useUserStore = defineStore(
  'user',
  () => {
    const token = ref('')
    const userInfo = ref<UserInfo | null>(null)

    const isLoggedIn = computed(() => token.value !== '')
    const nickname = computed(() => userInfo.value?.nickname || userInfo.value?.username || '')

    /** 清空登录态（401、登出时调用） */
    function reset() {
      token.value = ''
      userInfo.value = null
    }

    return { token, userInfo, isLoggedIn, nickname, reset }
  },
  { persist: true },
)
```

- [ ] **Step 5: 更新 `src/main.ts` 使用带插件的 pinia，并删除脚手架 counter store**

`src/main.ts` 全量替换为：

```ts
import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import { pinia } from './stores'

const app = createApp(App)

app.use(pinia)
app.use(router)

app.mount('#app')
```

删除文件 `src/stores/counter.ts`（脚手架残留，无任何引用）。

同时修正 `src/__tests__/App.spec.ts`：该测试当前**已经失败**（断言脚手架文案 `'You did it!'`，但 App.vue 已改为 `el-config-provider` + `router-view`），会阻塞本任务的全量测试收尾。全量替换为与当前 App.vue 一致的断言：

```ts
import { describe, it, expect } from 'vitest'

import { mount } from '@vue/test-utils'
import App from '../App.vue'

describe('App', () => {
  it('挂载成功并渲染 el-config-provider', () => {
    const wrapper = mount(App)
    expect(wrapper.findComponent({ name: 'ElConfigProvider' }).exists()).toBe(true)
  })
})
```

- [ ] **Step 6: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/user.spec.ts`
Expected: PASS（4 个用例）

- [ ] **Step 7: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS（含本任务已修正的 `App.spec.ts`）；type-check 无错误

- [ ] **Step 8: Commit**

```bash
git add src/stores/index.ts src/stores/user.ts src/main.ts src/__tests__/user.spec.ts src/__tests__/App.spec.ts
git rm src/stores/counter.ts
git commit -m "feat(ecboot-admin): 添加用户 store 与 pinia 持久化插件"
```

---

### Task 3: axios 封装与拦截器

**Files:**
- Create: `src/utils/request.ts`
- Test: `src/__tests__/request.spec.ts`

**Interfaces:**
- Consumes: `useUserStore()` 的 `token`、`reset()`（Task 2）；`import.meta.env.VITE_API_BASE_URL`（Task 1）
- Produces: `get<T>(url, config?): Promise<T>`、`post<T>(url, data?, config?): Promise<T>`、`put<T>(url, data?, config?): Promise<T>`、`del<T>(url, config?): Promise<T>`（均已解包，直接返回业务数据）；`request`（axios 实例本身，Task 4 的 api 层使用 get/post）

- [ ] **Step 1: 写失败测试 `src/__tests__/request.spec.ts`**

```ts
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { AxiosError } from 'axios'
import { createPinia, setActivePinia } from 'pinia'
import { del, get, post } from '@/utils/request'
import { useUserStore } from '@/stores/user'

vi.mock('element-plus', () => ({
  ElMessage: { error: vi.fn(), success: vi.fn() },
}))

import { ElMessage } from 'element-plus'

/** 短路网络层：让 axios 实例直接返回构造的响应 */
function useMockAdapter(payload: unknown, status = 200) {
  return vi.fn(async (config: Record<string, unknown>) => ({
    data: payload,
    status,
    statusText: 'OK',
    headers: {},
    config,
  }))
}

describe('request 拦截器', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.stubGlobal('location', { pathname: '/dashboard', search: '', href: '' })
  })

  afterEach(() => {
    vi.unstubAllGlobals()
    vi.clearAllMocks()
  })

  it('请求头注入 Bearer token', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const userStore = useUserStore()
    userStore.token = 'jwt-token'

    await get('/admin/auth/profile')

    expect(adapter).toHaveBeenCalledTimes(1)
    const config = adapter.mock.calls[0]?.[0] as { headers: Record<string, string> }
    expect(config.headers.Authorization).toBe('Bearer jwt-token')
  })

  it('无 token 时不注入 Authorization', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await get('/anything')

    const config = adapter.mock.calls[0]?.[0] as { headers: Record<string, string> }
    expect(config.headers.Authorization).toBeUndefined()
  })

  it('code=0 时解包返回 data', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: { id: 1, name: 'x' } })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const data = await post<{ id: number; name: string }>('/anything', { a: 1 })

    expect(data).toEqual({ id: 1, name: 'x' })
    expect(ElMessage.error).not.toHaveBeenCalled()
  })

  it('code 非 0 时 reject 并弹出错误信息', async () => {
    const adapter = useMockAdapter({ code: 50001, message: '用户名或密码错误', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await expect(post('/admin/auth/login', {})).rejects.toThrow('用户名或密码错误')
    expect(ElMessage.error).toHaveBeenCalledWith('用户名或密码错误')
  })

  it('业务码 401 时清空登录态并跳转登录页（带 redirect）', async () => {
    const adapter = useMockAdapter({ code: 401, message: '登录已过期', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const userStore = useUserStore()
    userStore.token = 'expired-token'

    await expect(get('/admin/auth/profile')).rejects.toThrow()

    expect(userStore.token).toBe('')
    expect(window.location.href).toBe(`/login?redirect=${encodeURIComponent('/dashboard')}`)
  })

  it('HTTP 401 同样清空登录态并跳转', async () => {
    const adapter = useMockAdapter({ code: 401, message: 'Unauthorized', data: null }, 401)
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const userStore = useUserStore()
    userStore.token = 'expired-token'

    await expect(get('/admin/auth/profile')).rejects.toThrow()

    expect(userStore.token).toBe('')
    expect(window.location.href).toBe(`/login?redirect=${encodeURIComponent('/dashboard')}`)
  })

  it('已在登录页时 401 不重复跳转', async () => {
    vi.stubGlobal('location', { pathname: '/login', search: '', href: '' })
    const adapter = useMockAdapter({ code: 401, message: '登录已过期', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await expect(get('/anything')).rejects.toThrow()

    expect(window.location.href).toBe('')
  })

  it('HTTP 500 弹出服务端错误文案', async () => {
    const adapter = useMockAdapter({ message: 'server error' }, 500)
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await expect(get('/anything')).rejects.toThrow()
    expect(ElMessage.error).toHaveBeenCalledWith('服务器开小差了，请稍后重试')
  })

  it('网络异常（无 response）弹出网络错误文案', async () => {
    const { request } = await import('@/utils/request')
    request.defaults.adapter = (async () => {
      throw new AxiosError('Network Error')
    }) as never

    await expect(del('/anything')).rejects.toThrow()
    expect(ElMessage.error).toHaveBeenCalledWith('网络异常，请检查网络连接')
  })
})
```

说明：每个用例通过动态 `import('@/utils/request')` 获取实例并替换 `defaults.adapter`（纯内存响应，不发起真实网络请求）；`ElMessage` 整体 mock 避免 jsdom 弹窗副作用；`location` 用 `vi.stubGlobal` 换成可断言的普通对象。

- [ ] **Step 2: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/request.spec.ts`
Expected: FAIL，报错 `Cannot find module '@/utils/request'` 或等效解析错误

- [ ] **Step 3: 创建 `src/utils/request.ts`**

```ts
import axios, { AxiosError, type AxiosRequestConfig } from 'axios'
import { ElMessage } from 'element-plus'
import { useUserStore } from '@/stores/user'

const REQUEST_TIMEOUT = 10_000

export const request = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  timeout: REQUEST_TIMEOUT,
})

request.interceptors.request.use((config) => {
  const userStore = useUserStore()
  if (userStore.token) {
    config.headers.Authorization = `Bearer ${userStore.token}`
  }
  return config
})

/** 未授权（token 失效/缺失）：清空登录态并跳转登录页，携带回跳地址 */
function handleUnauthorized(): void {
  useUserStore().reset()
  const { pathname, search } = window.location
  if (pathname !== '/login') {
    window.location.href = `/login?redirect=${encodeURIComponent(pathname + search)}`
  }
}

/** HTTP 层错误分类文案 */
function getHttpErrorMessage(error: AxiosError): string {
  if (error.code === 'ECONNABORTED') {
    return '请求超时，请稍后重试'
  }
  if (!error.response) {
    return '网络异常，请检查网络连接'
  }
  const { status } = error.response
  if (status >= 500) {
    return '服务器开小差了，请稍后重试'
  }
  if (status === 404) {
    return '请求的资源不存在'
  }
  return `请求失败（${status}）`
}

request.interceptors.response.use(
  (response) => {
    const res = response.data as { code: number; message: string; data: unknown }
    if (res.code === 0) {
      // 解包信封：直接返回业务数据，类型由下方泛型封装保证
      return res.data
    }
    if (res.code === 401) {
      handleUnauthorized()
    } else {
      ElMessage.error(res.message || '请求失败')
    }
    return Promise.reject(new Error(res.message || '请求失败'))
  },
  (error: AxiosError) => {
    if (error.response?.status === 401) {
      handleUnauthorized()
    } else {
      ElMessage.error(getHttpErrorMessage(error))
    }
    return Promise.reject(error instanceof Error ? error : new Error(String(error)))
  },
)

export function get<T>(url: string, config?: AxiosRequestConfig): Promise<T> {
  return request.get(url, config) as Promise<T>
}

export function post<T>(url: string, data?: unknown, config?: AxiosRequestConfig): Promise<T> {
  return request.post(url, data, config) as Promise<T>
}

export function put<T>(url: string, data?: unknown, config?: AxiosRequestConfig): Promise<T> {
  return request.put(url, data, config) as Promise<T>
}

export function del<T>(url: string, config?: AxiosRequestConfig): Promise<T> {
  return request.delete(url, config) as Promise<T>
}
```

- [ ] **Step 4: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/request.spec.ts`
Expected: PASS（9 个用例）

- [ ] **Step 5: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS；type-check 无错误

- [ ] **Step 6: Commit**

```bash
git add src/utils/request.ts src/__tests__/request.spec.ts
git commit -m "feat(ecboot-admin): 封装 axios 请求与响应拦截器"
```

---

### Task 4: 登录/登出/用户信息 API 与 store actions

**Files:**
- Create: `src/api/auth.ts`
- Modify: `src/stores/user.ts`
- Test: `src/__tests__/user.spec.ts`（追加用例）

**Interfaces:**
- Consumes: `get/post`（Task 3）；`LoginParams/LoginResult/UserInfo`（Task 1）；`useUserStore` 的 `reset()`（Task 2）
- Produces: `loginApi(data: LoginParams): Promise<LoginResult>`、`logoutApi(): Promise<null>`、`getProfileApi(): Promise<UserInfo>`；store 新增 `login(params: LoginParams): Promise<void>`、`logout(): Promise<void>`（Task 6 的 AppHeader、Task 7 的 LoginView 使用）

- [ ] **Step 1: 在 `src/__tests__/user.spec.ts` 顶部追加 API mock 与新用例**

在文件顶部（`describe` 之前）加入：

```ts
import { vi } from 'vitest'

vi.mock('@/api/auth', () => ({
  loginApi: vi.fn(),
  logoutApi: vi.fn(),
  getProfileApi: vi.fn(),
}))

import { loginApi, logoutApi, getProfileApi } from '@/api/auth'
import type { UserInfo } from '@/types/api'

const mockUserInfo: UserInfo = { id: 1, username: 'admin', nickname: '管理员' }
```

并在 `describe('user store', ...)` 内追加用例：

```ts
  it('login 成功：写入 token 与用户信息', async () => {
    vi.mocked(loginApi).mockResolvedValue({ access_token: 'jwt-abc' })
    vi.mocked(getProfileApi).mockResolvedValue(mockUserInfo)

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(loginApi).toHaveBeenCalledWith({ username: 'admin', password: '123456' })
    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toEqual(mockUserInfo)
    expect(userStore.isLoggedIn).toBe(true)
  })

  it('login 时拉取用户信息失败不阻塞登录', async () => {
    vi.mocked(loginApi).mockResolvedValue({ access_token: 'jwt-abc' })
    vi.mocked(getProfileApi).mockRejectedValue(new Error('profile failed'))

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toBeNull()
  })

  it('login 接口失败：保持未登录状态', async () => {
    vi.mocked(loginApi).mockRejectedValue(new Error('用户名或密码错误'))

    const userStore = useUserStore()

    await expect(userStore.login({ username: 'admin', password: 'bad' })).rejects.toThrow(
      '用户名或密码错误',
    )
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })

  it('logout：调用接口后清空登录态', async () => {
    vi.mocked(logoutApi).mockResolvedValue(null)

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'
    userStore.userInfo = mockUserInfo

    await userStore.logout()

    expect(logoutApi).toHaveBeenCalled()
    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
  })

  it('logout：接口失败也强制清空本地登录态', async () => {
    vi.mocked(logoutApi).mockRejectedValue(new Error('network down'))

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'

    await expect(userStore.logout()).rejects.toThrow('network down')
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })
```

- [ ] **Step 2: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/user.spec.ts`
Expected: FAIL，报错 `Cannot find module '@/api/auth'`，且新用例报 `userStore.login is not a function`

- [ ] **Step 3: 创建 `src/api/auth.ts`**

```ts
import { get, post } from '@/utils/request'
import type { LoginParams, LoginResult, UserInfo } from '@/types/api'

/** 登录 */
export function loginApi(data: LoginParams): Promise<LoginResult> {
  return post<LoginResult>('/admin/auth/login', data)
}

/** 退出登录 */
export function logoutApi(): Promise<null> {
  return post<null>('/admin/auth/logout')
}

/** 当前登录用户信息 */
export function getProfileApi(): Promise<UserInfo> {
  return get<UserInfo>('/admin/auth/profile')
}
```

- [ ] **Step 4: `src/stores/user.ts` 增加 login/logout actions**

在 `reset` 函数定义之后、`return` 之前加入：

```ts
    /** 登录：写入 token 并尽力拉取用户信息（拉取失败不阻塞登录） */
    async function login(params: LoginParams) {
      const { access_token } = await loginApi(params)
      token.value = access_token
      try {
        userInfo.value = await getProfileApi()
      } catch {
        userInfo.value = null
      }
    }

    /** 登出：调用登出接口，无论成败都清空本地登录态 */
    async function logout() {
      try {
        await logoutApi()
      } finally {
        reset()
      }
    }
```

同步修改导入与返回值：

```ts
import type { LoginParams, UserInfo } from '@/types/api'
import { loginApi, logoutApi, getProfileApi } from '@/api/auth'
```

```ts
    return { token, userInfo, isLoggedIn, nickname, login, logout, reset }
```

- [ ] **Step 5: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/user.spec.ts`
Expected: PASS（9 个用例：Task 2 的 4 个 + 新增 5 个）

- [ ] **Step 6: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS；type-check 无错误

- [ ] **Step 7: Commit**

```bash
git add src/api/auth.ts src/stores/user.ts src/__tests__/user.spec.ts
git commit -m "feat(ecboot-admin): 对接登录/登出/用户信息 API"
```

---

### Task 5: 路由守卫、NProgress 与占位页面

**Files:**
- Modify: `src/router/index.ts`
- Create: `src/router/guard.ts`
- Create: `src/types/router.d.ts`
- Create: `src/views/DashboardView.vue`
- Create: `src/views/NotFoundView.vue`
- Modify: `src/main.ts`
- Modify: `package.json`（新增 devDependency `@types/nprogress`）
- Test: `src/__tests__/guard.spec.ts`

**Interfaces:**
- Consumes: `useUserStore()` 的 `isLoggedIn`（Task 2/4）
- Produces: `setupRouterGuard(router: Router): void`（main.ts 调用一次）；路由 `name`：`login`、`dashboard`、`not-found`；`meta.title?: string` 类型增强（Task 6 的 AppHeader 读取）；本任务路由表为过渡形态（`/` 顶层直接挂 DashboardView），Task 6 将重构为 Layout 包裹

- [ ] **Step 1: 安装 nprogress 类型声明（唯一新增依赖）**

Run: `pnpm add -D @types/nprogress`
Expected: 安装成功，`package.json` devDependencies 出现 `@types/nprogress`

- [ ] **Step 2: 写失败测试 `src/__tests__/guard.spec.ts`**

```ts
import { beforeEach, describe, expect, it } from 'vitest'
import { createRouter, createMemoryHistory, type Router } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import { setupRouterGuard } from '@/router/guard'
import { useUserStore } from '@/stores/user'

/** 用 memory history 构造独立测试路由（不依赖真实视图组件），避免守卫重复注册 */
function buildRouter(): Router {
  const router = createRouter({
    history: createMemoryHistory(),
    routes: [
      { path: '/login', component: { template: '<div>login</div>' }, meta: { title: '登录' } },
      {
        path: '/dashboard',
        component: { template: '<div>dashboard</div>' },
        meta: { title: '仪表盘' },
      },
      { path: '/:pathMatch(.*)*', component: { template: '<div>404</div>' }, meta: { title: '404' } },
    ],
  })
  setupRouterGuard(router)
  return router
}

describe('router guard', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('未登录访问受保护页：重定向登录页并携带 redirect', async () => {
    const router = buildRouter()

    await router.push('/dashboard')

    expect(router.currentRoute.value.path).toBe('/login')
    expect(router.currentRoute.value.query.redirect).toBe('/dashboard')
  })

  it('未登录访问未知路径：先跳登录而非 404', async () => {
    const router = buildRouter()

    await router.push('/foo/bar')

    expect(router.currentRoute.value.path).toBe('/login')
    expect(router.currentRoute.value.query.redirect).toBe('/foo/bar')
  })

  it('已登录访问 /login：重定向仪表盘', async () => {
    const router = buildRouter()
    useUserStore().token = 'jwt-token'

    await router.push('/login')

    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('已登录访问受保护页：正常放行', async () => {
    const router = buildRouter()
    useUserStore().token = 'jwt-token'

    await router.push('/dashboard')

    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('afterEach 设置 document.title', async () => {
    const router = buildRouter()
    useUserStore().token = 'jwt-token'

    await router.push('/dashboard')

    expect(document.title).toBe('仪表盘 - ECBoot')
  })
})
```

- [ ] **Step 3: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/guard.spec.ts`
Expected: FAIL，报错 `Cannot find module '@/router/guard'` 或等效解析错误

- [ ] **Step 4: 创建 `src/types/router.d.ts`（RouteMeta 类型增强）**

```ts
import 'vue-router'

declare module 'vue-router' {
  interface RouteMeta {
    /** 页面标题，用于 document.title 与顶栏展示 */
    title?: string
  }
}
```

- [ ] **Step 5: 创建 `src/router/guard.ts`**

```ts
import type { Router } from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { useUserStore } from '@/stores/user'

const APP_NAME = 'ECBoot'

/** 无需登录即可访问的路由 */
const WHITELIST = ['/login']

/** 注册全局路由守卫：登录校验 + NProgress + 页面标题 */
export function setupRouterGuard(router: Router): void {
  router.beforeEach((to) => {
    NProgress.start()
    const userStore = useUserStore()

    if (to.path === '/login') {
      // 已登录再访问登录页，直接送回仪表盘
      return userStore.isLoggedIn ? { path: '/dashboard', replace: true } : true
    }
    if (WHITELIST.includes(to.path)) {
      return true
    }
    if (!userStore.isLoggedIn) {
      return { path: '/login', query: { redirect: to.fullPath }, replace: true }
    }
    return true
  })

  router.afterEach((to) => {
    document.title = to.meta.title ? `${to.meta.title} - ${APP_NAME}` : APP_NAME
    NProgress.done()
  })
}
```

- [ ] **Step 6: 重写 `src/router/index.ts`（过渡形态：`/` 顶层直接挂仪表盘；Task 6 改为 Layout 包裹）**

```ts
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { title: '登录' },
    },
    {
      path: '/',
      name: 'dashboard',
      component: () => import('@/views/DashboardView.vue'),
      meta: { title: '仪表盘' },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/NotFoundView.vue'),
      meta: { title: '404' },
    },
  ],
})

export default router
```

注意：登录页改为路由懒加载（`() => import(...)`），与守卫的白名单判断（按 `path`）配合无时序问题。

- [ ] **Step 7: 创建占位视图 `src/views/DashboardView.vue`**

```vue
<script setup lang="ts">
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'DashboardView' })

const userStore = useUserStore()
</script>

<template>
  <div class="dashboard">
    <el-card>
      <template #header>仪表盘</template>
      <p>欢迎回来，{{ userStore.nickname || '管理员' }}！</p>
      <p class="dashboard-tip">ECBoot 管理后台基础框架已就绪，后续功能模块将在各自目录下扩展。</p>
    </el-card>
  </div>
</template>

<style scoped>
.dashboard-tip {
  margin-top: 8px;
  font-size: 13px;
  color: #909399;
}
</style>
```

- [ ] **Step 8: 创建 `src/views/NotFoundView.vue`**

```vue
<script setup lang="ts">
defineOptions({ name: 'NotFoundView' })
</script>

<template>
  <div class="not-found">
    <el-result icon="warning" title="404" sub-title="抱歉，您访问的页面不存在">
      <template #extra>
        <router-link to="/">
          <el-button type="primary">返回首页</el-button>
        </router-link>
      </template>
    </el-result>
  </div>
</template>

<style scoped>
.not-found {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}
</style>
```

- [ ] **Step 9: `src/main.ts` 注册守卫（在 `app.use(router)` 之前调用）**

```ts
import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import { setupRouterGuard } from './router/guard'
import { pinia } from './stores'

const app = createApp(App)

app.use(pinia)
setupRouterGuard(router)
app.use(router)

app.mount('#app')
```

说明：`setupRouterGuard` 只是注册回调，回调执行时 pinia 已激活（`app.use(pinia)` 在前），守卫内 `useUserStore()` 安全。

- [ ] **Step 10: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/guard.spec.ts`
Expected: PASS（5 个用例）

- [ ] **Step 11: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS（现有 `App.spec.ts` mount App.vue 不经过 router，不受影响）；type-check 无错误

- [ ] **Step 12: Commit**

```bash
git add src/router/index.ts src/router/guard.ts src/types/router.d.ts src/views/DashboardView.vue src/views/NotFoundView.vue src/main.ts src/__tests__/guard.spec.ts package.json pnpm-lock.yaml
git commit -m "feat(ecboot-admin): 添加路由守卫、NProgress 与占位页面"
```

---

### Task 6: 后台框架布局 Layout

**Files:**
- Create: `src/layout/index.vue`
- Create: `src/layout/components/SidebarMenu.vue`
- Create: `src/layout/components/AppHeader.vue`
- Modify: `src/router/index.ts`（重构为 Layout 包裹）
- Test: `src/__tests__/AppHeader.spec.ts`

**Interfaces:**
- Consumes: `useUserStore()` 的 `nickname`、`logout()`（Task 4）；`meta.title`（Task 5 类型增强）
- Produces: 路由 `/` → Layout（name: `layout`），子路由 `/dashboard`（name: `dashboard`，与 Task 5 的 `name` 保持一致，守卫不依赖 name 所以不受影响）；后续所有业务页面将作为 Layout 的 children 挂载

- [ ] **Step 1: 写失败测试 `src/__tests__/AppHeader.spec.ts`**

```ts
import { beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createRouter, createMemoryHistory, type Router } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import AppHeader from '@/layout/components/AppHeader.vue'
import { useUserStore } from '@/stores/user'

vi.mock('@/api/auth', () => ({
  loginApi: vi.fn(),
  logoutApi: vi.fn(),
  getProfileApi: vi.fn(),
}))

import { logoutApi } from '@/api/auth'
import type { UserInfo } from '@/types/api'

function buildRouter(): Router {
  return createRouter({
    history: createMemoryHistory(),
    routes: [
      { path: '/login', component: { template: '<div>login</div>' } },
      { path: '/', component: { template: '<div>home</div>' }, children: [] },
    ],
  })
}

describe('AppHeader', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.mocked(logoutApi).mockResolvedValue(null)
  })

  it('展示用户昵称', () => {
    const userStore = useUserStore()
    userStore.token = 'jwt-token'
    const userInfo: UserInfo = { id: 1, username: 'admin', nickname: '管理员' }
    userStore.userInfo = userInfo

    const wrapper = mount(AppHeader, { global: { plugins: [buildRouter()] } })

    expect(wrapper.text()).toContain('管理员')
  })

  it('退出登录：调用接口、清空登录态并跳转登录页', async () => {
    const router = buildRouter()
    await router.push('/')
    const userStore = useUserStore()
    userStore.token = 'jwt-token'

    const wrapper = mount(AppHeader, { global: { plugins: [router] } })
    const dropdown = wrapper.findComponent({ name: 'ElDropdown' })
    expect(dropdown.exists()).toBe(true)

    dropdown.vm.$emit('command', 'logout')
    await flushPromises()

    expect(logoutApi).toHaveBeenCalled()
    expect(userStore.token).toBe('')
    expect(router.currentRoute.value.path).toBe('/login')
  })
})
```

- [ ] **Step 2: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/AppHeader.spec.ts`
Expected: FAIL，报错 `Cannot find module '@/layout/components/AppHeader.vue'`

- [ ] **Step 3: 创建 `src/layout/components/SidebarMenu.vue`**

```vue
<script setup lang="ts">
import type { Component } from 'vue'
import { Odometer } from '@element-plus/icons-vue'
import { useRoute } from 'vue-router'

defineOptions({ name: 'SidebarMenu' })

interface MenuItem {
  path: string
  title: string
  icon: Component
}

const route = useRoute()

const menuItems: MenuItem[] = [{ path: '/dashboard', title: '仪表盘', icon: Odometer }]
</script>

<template>
  <el-menu class="sidebar-menu" router :default-active="route.path">
    <el-menu-item v-for="item in menuItems" :key="item.path" :index="item.path">
      <el-icon><component :is="item.icon" /></el-icon>
      <span>{{ item.title }}</span>
    </el-menu-item>
  </el-menu>
</template>

<style scoped>
.sidebar-menu {
  flex: 1;
  border-right: none;
  --el-menu-bg-color: transparent;
  --el-menu-text-color: rgb(255 255 255 / 65%);
  --el-menu-hover-bg-color: rgb(255 255 255 / 8%);
}
</style>
```

- [ ] **Step 4: 创建 `src/layout/components/AppHeader.vue`**

```vue
<script setup lang="ts">
import { ArrowDown, UserFilled } from '@element-plus/icons-vue'
import { ElMessage } from 'element-plus'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'AppHeader' })

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

async function handleCommand(command: string) {
  if (command !== 'logout') {
    return
  }
  try {
    await userStore.logout()
  } finally {
    ElMessage.success('已退出登录')
    router.replace('/login')
  }
}
</script>

<template>
  <div class="app-header">
    <div class="header-title">{{ route.meta.title ?? '' }}</div>

    <el-dropdown trigger="click" @command="handleCommand">
      <span class="header-user">
        <el-icon><UserFilled /></el-icon>
        <span>{{ userStore.nickname || '用户' }}</span>
        <el-icon class="header-arrow"><ArrowDown /></el-icon>
      </span>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item command="logout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </div>
</template>

<style scoped>
.app-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
}

.header-title {
  font-size: 15px;
  font-weight: 500;
  color: #303133;
}

.header-user {
  display: flex;
  gap: 6px;
  align-items: center;
  font-size: 14px;
  color: #303133;
  cursor: pointer;
}

.header-arrow {
  font-size: 12px;
  color: #909399;
}
</style>
```

- [ ] **Step 5: 创建 `src/layout/index.vue`**

```vue
<script setup lang="ts">
import SidebarMenu from './components/SidebarMenu.vue'
import AppHeader from './components/AppHeader.vue'

defineOptions({ name: 'AppLayout' })
</script>

<template>
  <el-container class="layout">
    <el-aside class="layout-aside" width="208px">
      <div class="layout-logo">ECBoot</div>
      <SidebarMenu />
    </el-aside>

    <el-container>
      <el-header class="layout-header" height="56px">
        <AppHeader />
      </el-header>
      <el-main class="layout-main">
        <router-view />
      </el-main>
    </el-container>
  </el-container>
</template>

<style scoped>
.layout {
  height: 100%;
}

.layout-aside {
  display: flex;
  flex-direction: column;
  background: #001529;
}

.layout-logo {
  height: 56px;
  font-size: 18px;
  font-weight: 600;
  line-height: 56px;
  color: #fff;
  text-align: center;
}

.layout-header {
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}

.layout-main {
  background: #f5f7fa;
}
</style>
```

- [ ] **Step 6: 重构 `src/router/index.ts` 为 Layout 包裹**

```ts
import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@/layout/index.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { title: '登录' },
    },
    {
      path: '/',
      name: 'layout',
      component: Layout,
      redirect: '/dashboard',
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: () => import('@/views/DashboardView.vue'),
          meta: { title: '仪表盘' },
        },
      ],
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/NotFoundView.vue'),
      meta: { title: '404' },
    },
  ],
})

export default router
```

说明：守卫白名单按 `path` 判断且 `redirect` 指向 `/dashboard`，路由结构变化不影响守卫行为；Layout 静态导入（首屏框架页），业务页面保持懒加载。

- [ ] **Step 7: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/AppHeader.spec.ts`
Expected: PASS（2 个用例）

- [ ] **Step 8: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS；type-check 无错误

- [ ] **Step 9: Commit**

```bash
git add src/layout src/router/index.ts src/__tests__/AppHeader.spec.ts
git commit -m "feat(ecboot-admin): 添加后台框架布局 Layout"
```

---

### Task 7: 登录页对接真实接口

**Files:**
- Modify: `src/views/LoginView.vue`
- Test: `src/__tests__/LoginView.spec.ts`

**Interfaces:**
- Consumes: `useUserStore().login(params)`（Task 4）；`route.query.redirect`（Task 5 守卫写入）
- Produces: 登录成功后跳转 `redirect` 参数指定页或 `/`；完整的「未登录 → 守卫拦截 → 登录 → 回跳」闭环

- [ ] **Step 1: 写失败测试 `src/__tests__/LoginView.spec.ts`**

```ts
import { beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createRouter, createMemoryHistory, type Router } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import LoginView from '@/views/LoginView.vue'

vi.mock('@/api/auth', () => ({
  loginApi: vi.fn(),
  logoutApi: vi.fn(),
  getProfileApi: vi.fn(),
}))

import { getProfileApi, loginApi } from '@/api/auth'
import type { UserInfo } from '@/types/api'

const mockUserInfo: UserInfo = { id: 1, username: 'admin', nickname: '管理员' }

function buildRouter(initialUrl = '/'): Router {
  const router = createRouter({
    history: createMemoryHistory(),
    routes: [
      { path: '/login', component: LoginView, meta: { title: '登录' } },
      {
        path: '/dashboard',
        component: { template: '<div>dashboard</div>' },
        meta: { title: '仪表盘' },
      },
    ],
  })
  router.push(initialUrl)
  return router
}

/** 填写用户名密码并提交表单 */
async function fillAndSubmit(wrapper: Awaited<ReturnType<typeof mount>>) {
  await wrapper.find('input[name="username"]').setValue('admin')
  await wrapper.find('input[name="password"]').setValue('123456')
  await wrapper.find('form').trigger('submit')
  await flushPromises()
}

describe('LoginView', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    localStorage.clear()
    vi.mocked(loginApi).mockResolvedValue({ access_token: 'jwt-abc' })
    vi.mocked(getProfileApi).mockResolvedValue(mockUserInfo)
  })

  it('渲染品牌与登录表单', () => {
    const wrapper = mount(LoginView, { global: { plugins: [buildRouter()] } })

    expect(wrapper.text()).toContain('ECBoot')
    expect(wrapper.find('input[name="username"]').exists()).toBe(true)
    expect(wrapper.find('input[name="password"]').exists()).toBe(true)
  })

  it('空表单提交：被前端校验拦截，不调用登录接口', async () => {
    const wrapper = mount(LoginView, { global: { plugins: [buildRouter()] } })

    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(loginApi).not.toHaveBeenCalled()
  })

  it('登录成功：调用接口、存储 token 并跳转仪表盘', async () => {
    const router = buildRouter('/login')
    const wrapper = mount(LoginView, { global: { plugins: [router] } })

    await fillAndSubmit(wrapper)

    const userStore = useLoginStore()
    expect(loginApi).toHaveBeenCalledWith({ username: 'admin', password: '123456' })
    expect(userStore.token).toBe('jwt-abc')
    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('携带 redirect 参数：登录成功后回跳原目标页', async () => {
    const router = buildRouter('/login?redirect=%2Fdashboard')
    const wrapper = mount(LoginView, { global: { plugins: [router] } })

    await fillAndSubmit(wrapper)

    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('勾选记住用户名：登录成功后写入 localStorage', async () => {
    const router = buildRouter('/login')
    const wrapper = mount(LoginView, { global: { plugins: [router] } })
    await wrapper.find('input[name="username"]').setValue('admin')
    await wrapper.find('input[name="password"]').setValue('123456')

    await fillAndSubmit(wrapper)

    expect(localStorage.getItem('ecboot-admin-username')).toBe('admin')
  })
})

import { useUserStore as useLoginStore } from '@/stores/user'
```

说明：`el-input` 会把 `name` 透传给原生 `<input>`，因此可以直接 `find('input[name=...]')` + `setValue` 驱动 `v-model`；`el-form` 渲染真实 `<form>`，`trigger('submit')` 走 `@submit.prevent="handleLogin"`。`useLoginStore` 别名放文件底部以避免与组件 import 混淆（vitest 对 import 顺序不敏感，vi.mock 提升生效）。

- [ ] **Step 2: 运行测试确认失败**

Run: `pnpm exec vitest run src/__tests__/LoginView.spec.ts`
Expected: FAIL —— 现有 `handleLogin` 是 mock 延时（`setTimeout`），用例 3/4/5 断言失败（token 未写入、未跳转）

- [ ] **Step 3: 改造 `src/views/LoginView.vue` 的脚本部分**

`<script setup>` 替换为（模板与样式不动）：

```vue
<script setup lang="ts">
import { Lock, User } from '@element-plus/icons-vue'
import { ElMessage, type FormInstance, type FormRules } from 'element-plus'
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

defineOptions({ name: 'LoginView' })

const REMEMBER_KEY = 'ecboot-admin-username'

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const formRef = ref<FormInstance>()
const loading = ref(false)

const loginForm = reactive({
  username: '',
  password: '',
  remember: true,
})

const loginRules: FormRules<typeof loginForm> = {
  username: [
    { required: true, message: '请输入用户名', trigger: 'blur' },
    { min: 3, max: 30, message: '用户名长度为 3-30 个字符', trigger: 'blur' },
  ],
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' },
    { min: 6, max: 32, message: '密码长度为 6-32 位', trigger: 'blur' },
  ],
}

onMounted(() => {
  const saved = localStorage.getItem(REMEMBER_KEY)
  if (saved) {
    loginForm.username = saved
  }
})

async function handleLogin(): Promise<void> {
  const form = formRef.value
  if (!form) {
    return
  }
  try {
    await form.validate()
  } catch {
    return
  }

  loading.value = true
  try {
    await userStore.login({
      username: loginForm.username,
      password: loginForm.password,
    })

    if (loginForm.remember) {
      localStorage.setItem(REMEMBER_KEY, loginForm.username)
    } else {
      localStorage.removeItem(REMEMBER_KEY)
    }

    ElMessage.success('登录成功')
    const redirect = route.query.redirect
    await router.replace(typeof redirect === 'string' && redirect !== '' ? redirect : '/')
  } catch {
    // 错误提示已由 request 拦截器统一弹出
    return
  } finally {
    loading.value = false
  }
}
</script>
```

说明：移除 mock 延时与 TODO 注释；登录失败的具体提示由 request 拦截器弹出（`catch` 里的 `return` 保证空块不触发 lint）；「记住用户名」逻辑保持不变；`:loading` 绑定天然防重复提交。

- [ ] **Step 4: 运行测试确认通过**

Run: `pnpm exec vitest run src/__tests__/LoginView.spec.ts`
Expected: PASS（5 个用例）

- [ ] **Step 5: 全量测试 + 类型检查**

Run: `pnpm exec vitest run && pnpm type-check`
Expected: 全部 PASS；type-check 无错误

- [ ] **Step 6: Commit**

```bash
git add src/views/LoginView.vue src/__tests__/LoginView.spec.ts
git commit -m "feat(ecboot-admin): 登录页对接真实登录接口"
```

---

### Task 8: 最终验证与冒烟

**Files:**
- Modify: 可能有 lint/format 产生的格式修正

**Interfaces:**
- Consumes: 全部前序任务
- Produces: 通过 lint、format、type-check、全量测试与生产构建的最终状态

- [ ] **Step 1: 运行 lint 与格式化**

Run: `pnpm lint && pnpm format`
Expected: oxlint + eslint 无报错（`--fix` 自动修复格式问题）；prettier 完成格式化。若有文件被修改，检查 diff 是否仅为格式变化

- [ ] **Step 2: 类型检查 + 全量测试 + 生产构建**

Run: `pnpm type-check && pnpm exec vitest run && pnpm build`
Expected: 三者全部通过。`pnpm build` = type-check + vite build

- [ ] **Step 3: 人工冒烟（后端可启动时）**

Run: `php artisan serve`（仓库根，端口 8000）+ `pnpm dev`（packages/ecboot-admin），浏览器验证：

1. 未登录访问 `http://localhost:5173/` → 跳转 `/login`，顶部出现 NProgress 进度条
2. 输入错误凭据 → 弹出后端错误信息（后端未实现登录接口时，应看到「网络异常」或业务错误文案，而非白屏）
3. 登录成功 → 进入 `/dashboard`，侧边栏/顶栏/欢迎语正常，刷新页面登录态保持（localStorage）
4. 顶栏退出登录 → 回到 `/login`
5. 登录后手输 `/login` → 被送回 `/dashboard`
6. 访问不存在路径 → 404 页；「返回首页」→ 仪表盘

若后端暂不可启动，仅验证 1、3（本地 state 模拟）、4、5、6 项不受影响的部分与控制台无报错。

- [ ] **Step 4: 提交格式修正（如有）**

```bash
git add -A
git commit -m "chore(ecboot-admin): lint 格式化修正"
```

（若 Step 1 没有产生任何文件变更，则跳过本步骤）
