import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import Layout from '@/layout/index.vue'
import { LOGIN_PATH } from '@/consts/routes'
import { adminRoutes } from '@/router/modules/admin'
import { chatRoutes } from '@/router/modules/chat'
import { contentRoutes } from '@/router/modules/content'
import { financeRoutes } from '@/router/modules/finance'
import { marketingRoutes } from '@/router/modules/marketing'
import { orderRoutes } from '@/router/modules/order'
import { productRoutes } from '@/router/modules/product'
import { siteRoutes } from '@/router/modules/site'
import { statisticsRoutes } from '@/router/modules/statistics'
import { systemRoutes } from '@/router/modules/system'
import { userRoutes } from '@/router/modules/user'
import { wechatRoutes } from '@/router/modules/wechat'

/** Layout 下的一级域路由（顺序即菜单展示顺序），是菜单派生的单一事实源 */
const domainRoutes: RouteRecordRaw[] = [
  ...productRoutes,
  ...orderRoutes,
  ...marketingRoutes,
  ...userRoutes,
  ...financeRoutes,
  ...statisticsRoutes,
  ...contentRoutes,
  ...siteRoutes,
  ...chatRoutes,
  ...wechatRoutes,
  ...adminRoutes,
  ...systemRoutes,
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: LOGIN_PATH,
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
          meta: { title: '仪表盘', icon: 'Odometer' },
        },
        ...domainRoutes,
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
