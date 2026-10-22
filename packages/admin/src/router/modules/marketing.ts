import type { RouteRecordRaw } from 'vue-router'

/** 营销域路由 */
export const marketingRoutes: RouteRecordRaw[] = [
  {
    path: '/marketing',
    name: 'marketing',
    redirect: '/marketing/coupon',
    meta: { title: '营销', icon: 'Present' },
    children: [
      { path: 'coupon', name: 'marketing:coupon', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '优惠券', permission: 'marketing:coupon' } }, // TODO(批次5)
      { path: 'promotion', name: 'marketing:promotion', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '促销活动', permission: 'marketing:promotion' } }, // TODO(批次5)
      { path: 'flash-sale', name: 'marketing:flashSale', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '秒杀活动', permission: 'marketing:flashSale' } }, // TODO(批次5)
      { path: 'group-buy', name: 'marketing:groupBuy', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '拼团活动', permission: 'marketing:groupBuy' } }, // TODO(批次5)
      { path: 'bargain', name: 'marketing:bargain', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '砍价活动', permission: 'marketing:bargain' } }, // TODO(批次5)
      { path: 'assist', name: 'marketing:assist', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '助力活动', permission: 'marketing:assist' } }, // TODO(批次5)
      { path: 'distribution', name: 'marketing:distribution', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '分销员与规则', permission: 'marketing:distribution' } }, // TODO(批次6)
      { path: 'share-record', name: 'marketing:shareRecord', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '分享记录', permission: 'marketing:shareRecord', hidden: true } }, // TODO(批次6)
    ],
  },
]
