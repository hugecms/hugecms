import type { RouteRecordRaw } from 'vue-router'

/** 财务域路由：资金动作、单据与对账（含支付单/回调核对） */
export const financeRoutes: RouteRecordRaw[] = [
  {
    path: '/finance',
    name: 'finance',
    redirect: '/finance/withdraw',
    meta: { title: '财务', icon: 'Money' },
    children: [
      { path: 'withdraw', name: 'finance:withdraw', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '提现审核', permission: 'finance:withdraw' } }, // TODO(批次4)
      { path: 'account-log', name: 'finance:accountLog', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '资金流水', permission: 'finance:accountLog' } }, // TODO(批次4)
      { path: 'commission', name: 'finance:commission', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '佣金结算', permission: 'finance:commission' } }, // TODO(批次6)
      { path: 'invoice', name: 'finance:invoice', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '发票管理', permission: 'finance:invoice' } }, // TODO(批次3)
      { path: 'payment', name: 'finance:payment', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '支付单', permission: 'finance:payment' } }, // TODO(批次6)
      { path: 'callback', name: 'finance:callback', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '回调记录', permission: 'finance:callback' } }, // TODO(批次6)
      { path: 'reconciliation', name: 'finance:reconciliation', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '支付对账', permission: 'finance:reconciliation' } }, // TODO(批次6)
      { path: 'report', name: 'finance:report', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '财务报表', permission: 'finance:report' } }, // TODO(批次8)
    ],
  },
]
