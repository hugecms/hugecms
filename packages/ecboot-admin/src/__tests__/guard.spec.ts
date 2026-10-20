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
      {
        path: '/:pathMatch(.*)*',
        component: { template: '<div>404</div>' },
        meta: { title: '404' },
      },
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
