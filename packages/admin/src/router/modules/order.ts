import type { RouteRecordRaw } from 'vue-router'

/** 订单域路由（含履约配置：发货/配送方式/运费区域） */
export const orderRoutes: RouteRecordRaw[] = [
  {
    path: '/order',
    name: 'order',
    redirect: '/order/list',
    meta: { title: '订单', icon: 'Tickets' },
    children: [
      { path: 'list', name: 'order:list', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '订单列表', permission: 'order:list' } }, // TODO(批次3)
      { path: 'shipment', name: 'order:shipment', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '发货管理', permission: 'order:shipment' } }, // TODO(批次3)
      { path: 'refund', name: 'order:refund', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '退款售后', permission: 'order:refund' } }, // TODO(批次3)
      { path: 'method', name: 'order:shippingMethod', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '配送方式', permission: 'order:shippingMethod' } }, // TODO(批次8)
      { path: 'area', name: 'order:shippingArea', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '运费区域', permission: 'order:shippingArea' } }, // TODO(批次8)
    ],
  },
]
