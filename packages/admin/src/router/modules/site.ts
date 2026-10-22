import type { RouteRecordRaw } from 'vue-router'

/** 站点域路由 */
export const siteRoutes: RouteRecordRaw[] = [
  {
    path: '/site',
    name: 'site',
    redirect: '/site/ad',
    meta: { title: '站点', icon: 'Shop' },
    children: [
      { path: 'ad', name: 'site:ad', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '广告', permission: 'site:ad' } }, // TODO(批次8)
      { path: 'ad-position', name: 'site:adPosition', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '广告位', permission: 'site:adPosition' } }, // TODO(批次8)
      { path: 'nav', name: 'site:nav', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '导航', permission: 'site:nav' } }, // TODO(批次8)
      { path: 'link', name: 'site:link', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '友链', permission: 'site:link' } }, // TODO(批次8)
      { path: 'search-keyword', name: 'site:searchKeyword', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '搜索词', permission: 'site:searchKeyword' } }, // TODO(批次8)
    ],
  },
]
