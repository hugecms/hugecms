import type { RouteRecordRaw } from 'vue-router'

/** 客服域路由 */
export const chatRoutes: RouteRecordRaw[] = [
  {
    path: '/chat',
    name: 'chat',
    redirect: '/chat/session',
    meta: { title: '客服', icon: 'ChatDotRound' },
    children: [
      { path: 'session', name: 'chat:session', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '客服会话', permission: 'chat:session' } }, // TODO(批次10)
      { path: 'service', name: 'chat:service', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '客服坐席', permission: 'chat:service' } }, // TODO(批次10)
      { path: 'quick-reply', name: 'chat:quickReply', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '快捷回复', permission: 'chat:quickReply' } }, // TODO(批次10)
      { path: 'bot-rule', name: 'chat:botRule', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '机器人规则', permission: 'chat:botRule' } }, // TODO(批次10)
      { path: 'faq', name: 'chat:faq', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '常见问题', permission: 'chat:faq' } }, // TODO(批次10)
    ],
  },
]
