import type { RouteRecordRaw } from 'vue-router'

/** 微信域路由 */
export const wechatRoutes: RouteRecordRaw[] = [
  {
    path: '/wechat',
    name: 'wechat',
    redirect: '/wechat/fan',
    meta: { title: '微信', icon: 'ChatLineSquare' },
    children: [
      { path: 'fan', name: 'wechat:fan', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '粉丝管理', permission: 'wechat:fan' } }, // TODO(批次10)
      { path: 'message', name: 'wechat:message', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '微信消息', permission: 'wechat:message' } }, // TODO(批次10)
      { path: 'template', name: 'wechat:template', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '模板消息', permission: 'wechat:template' } }, // TODO(批次10)
      { path: 'subscription', name: 'wechat:subscription', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '订阅管理', permission: 'wechat:subscription' } }, // TODO(批次10)
      { path: 'qrcode', name: 'wechat:qrcode', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '二维码管理', permission: 'wechat:qrcode' } }, // TODO(批次10)
      { path: 'broadcast', name: 'wechat:broadcast', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '群发记录', permission: 'wechat:broadcast' } }, // TODO(批次10)
    ],
  },
]
