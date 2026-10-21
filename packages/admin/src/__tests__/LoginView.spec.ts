import { beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createRouter, createMemoryHistory, type Router } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import LoginView from '@/views/LoginView.vue'

vi.mock('@/api/auth', () => ({
  loginApi: vi.fn<(data: LoginParams) => Promise<LoginResult>>(),
  logoutApi: vi.fn<() => Promise<null>>(),
  getProfileApi: vi.fn<() => Promise<UserInfo>>(),
}))

import { getProfileApi, loginApi } from '@/api/auth'
import type { LoginParams, LoginResult, UserInfo } from '@/types/api'

const mockUserInfo: UserInfo = { id: 1, username: 'admin', nickname: '管理员' }

function buildRouter(initialUrl = '/'): Router {
  const router = createRouter({
    history: createMemoryHistory(),
    routes: [
      { path: '/login', component: LoginView, meta: { title: '登录' } },
      // 复刻生产路由表的 / → /dashboard 重定向（登录成功后 replace('/') 的落点）
      { path: '/', redirect: '/dashboard' },
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

/** 等待初始导航完成再挂载（复刻真实环境：用户到达登录页时导航已完成） */
async function mountLogin(initialUrl = '/login') {
  const router = buildRouter(initialUrl)
  await router.isReady()
  const wrapper = mount(LoginView, { global: { plugins: [router] } })
  return { wrapper, router }
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

  it('渲染品牌与登录表单', async () => {
    const { wrapper } = await mountLogin()

    expect(wrapper.text()).toContain('ECBoot')
    expect(wrapper.find('input[name="username"]').exists()).toBe(true)
    expect(wrapper.find('input[name="password"]').exists()).toBe(true)
  })

  it('空表单提交：被前端校验拦截，不调用登录接口', async () => {
    const { wrapper } = await mountLogin()

    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(loginApi).not.toHaveBeenCalled()
  })

  it('登录成功：调用接口、存储 token 并跳转仪表盘', async () => {
    const { wrapper, router } = await mountLogin('/login')

    await fillAndSubmit(wrapper)

    const userStore = useLoginStore()
    expect(loginApi).toHaveBeenCalledWith({ username: 'admin', password: '123456' })
    expect(userStore.token).toBe('jwt-abc')
    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('携带 redirect 参数：登录成功后回跳原目标页', async () => {
    const { wrapper, router } = await mountLogin('/login?redirect=%2Fdashboard')

    await fillAndSubmit(wrapper)

    expect(router.currentRoute.value.path).toBe('/dashboard')
  })

  it('勾选记住用户名：登录成功后写入 localStorage', async () => {
    const { wrapper } = await mountLogin('/login')
    await wrapper.find('input[name="username"]').setValue('admin')
    await wrapper.find('input[name="password"]').setValue('123456')

    await fillAndSubmit(wrapper)

    expect(localStorage.getItem('ecboot-admin-username')).toBe('admin')
  })
})

import { useUserStore as useLoginStore } from '@/stores/user'
