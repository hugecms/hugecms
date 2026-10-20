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
