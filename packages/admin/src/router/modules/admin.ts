import type { RouteRecordRaw } from 'vue-router'

/** 权限域路由 */
export const adminRoutes: RouteRecordRaw[] = [
  {
    path: '/admin',
    name: 'admin',
    redirect: '/admin/user',
    meta: { title: '权限', icon: 'Management' },
    children: [
      { path: 'user', name: 'admin:user', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '管理员', permission: 'admin:user' } }, // TODO(批次9)
      { path: 'role', name: 'admin:role', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '角色', permission: 'admin:role' } }, // TODO(批次9)
      { path: 'permission', name: 'admin:permission', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '权限', permission: 'admin:permission' } }, // TODO(批次9)
    ],
  },
]
