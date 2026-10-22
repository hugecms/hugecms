import type { RouteRecordRaw } from 'vue-router'

/** 系统域路由 */
export const systemRoutes: RouteRecordRaw[] = [
  {
    path: '/system',
    name: 'system',
    redirect: '/system/setting',
    meta: { title: '系统', icon: 'Setting' },
    children: [
      { path: 'setting', name: 'system:setting', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '系统设置', permission: 'system:setting' } }, // TODO(批次9)
      { path: 'payment-channel', name: 'system:paymentChannel', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '支付渠道', permission: 'system:paymentChannel' } }, // TODO(批次6)
      { path: 'app', name: 'system:app', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '应用注册', permission: 'system:app' } }, // TODO(批次9)
      { path: 'dict', name: 'system:dict', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '数据字典', permission: 'system:dict' } }, // TODO(批次9)
      { path: 'region', name: 'system:region', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '行政区域', permission: 'system:region' } }, // TODO(批次9)
      { path: 'attachment', name: 'system:attachment', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '附件管理', permission: 'system:attachment' } }, // TODO(批次9)
      { path: 'sms-log', name: 'system:smsLog', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '短信记录', permission: 'system:smsLog' } }, // TODO(批次10)
      { path: 'sensitive-word', name: 'system:sensitiveWord', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '敏感词', permission: 'system:sensitiveWord' } }, // TODO(批次10)
      { path: 'ip-blacklist', name: 'system:ipBlacklist', component: () => import('@/views/PlaceholderView.vue'), meta: { title: 'IP黑名单', permission: 'system:ipBlacklist' } }, // TODO(批次10)
      { path: 'operation-log', name: 'system:operationLog', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '操作日志', permission: 'system:operationLog' } }, // TODO(批次10)
      { path: 'login-log', name: 'system:loginLog', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '登录日志', permission: 'system:loginLog' } }, // TODO(批次10)
      { path: 'risk-rule', name: 'system:riskRule', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '风控规则', permission: 'system:riskRule' } }, // TODO(批次10)
      { path: 'risk-event', name: 'system:riskEvent', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '风控事件', permission: 'system:riskEvent' } }, // TODO(批次10)
    ],
  },
]
