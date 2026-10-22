import type { RouteRecordRaw } from 'vue-router'

/** 会员域路由 */
export const userRoutes: RouteRecordRaw[] = [
  {
    path: '/user',
    name: 'user',
    redirect: '/user/list',
    meta: { title: '会员', icon: 'User' },
    children: [
      { path: 'list', name: 'user:list', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '会员列表', permission: 'user:list' } }, // TODO(批次4)
      { path: 'rank', name: 'user:rank', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '会员等级', permission: 'user:rank' } }, // TODO(批次4)
    ],
  },
]
