import { beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createRouter, createMemoryHistory, type Router } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import AppHeader from '@/layout/components/AppHeader.vue'
import { useUserStore } from '@/stores/user'

vi.mock('@/api/dashboard', () => ({
  dashboardTodoApi: vi.fn().mockResolvedValue({
    pendingShipments: 0,
    pendingRefunds: 0,
    pendingWithdrawals: 0,
    lowStock: 0,
    pendingRiskAppeals: 0,
  }),
}))

vi.mock('@/api/auth', () => ({
  authLoginApi: vi.fn(),
  authLogoutApi: vi.fn(),
  authMeApi: vi.fn(),
}))

import { authLogoutApi } from '@/api/auth'
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
    vi.mocked(authLogoutApi).mockResolvedValue(null)
  })

  it('展示用户昵称', () => {
    const userStore = useUserStore()
    userStore.token = 'jwt-token'
    const userInfo: UserInfo = { id: 1, username: '管理员' }
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

    expect(authLogoutApi).toHaveBeenCalled()
    expect(userStore.token).toBe('')
    expect(router.currentRoute.value.path).toBe('/login')
  })
})
