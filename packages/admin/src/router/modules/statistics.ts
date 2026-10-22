import type { RouteRecordRaw } from 'vue-router'

/** 统计域路由 */
export const statisticsRoutes: RouteRecordRaw[] = [
  {
    path: '/statistics',
    name: 'statistics',
    redirect: '/statistics/daily',
    meta: { title: '统计', icon: 'DataAnalysis' },
    children: [
      { path: 'daily', name: 'statistics:daily', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '每日统计', permission: 'statistics:daily' } }, // TODO(批次8)
      { path: 'trade', name: 'statistics:trade', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '交易分析', permission: 'statistics:trade' } }, // TODO(批次8)
      { path: 'marketing', name: 'statistics:marketing', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '营销分析', permission: 'statistics:marketing' } }, // TODO(批次8)
      { path: 'distribution', name: 'statistics:distribution', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '分销分析', permission: 'statistics:distribution' } }, // TODO(批次8)
      { path: 'product', name: 'statistics:product', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '商品分析', permission: 'statistics:product' } }, // TODO(批次8)
    ],
  },
]
