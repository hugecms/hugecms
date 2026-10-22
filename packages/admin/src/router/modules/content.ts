import type { RouteRecordRaw } from 'vue-router'

/** 内容域路由（含表单收集） */
export const contentRoutes: RouteRecordRaw[] = [
  {
    path: '/content',
    name: 'content',
    redirect: '/content/article',
    meta: { title: '内容', icon: 'Document' },
    children: [
      { path: 'article', name: 'content:article', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '文章', permission: 'content:article' } }, // TODO(批次7)
      { path: 'category', name: 'content:category', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '文章分类', permission: 'content:category' } }, // TODO(批次7)
      { path: 'comment', name: 'content:comment', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '评论', permission: 'content:comment' } }, // TODO(批次7)
      { path: 'topic', name: 'content:topic', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '专题', permission: 'content:topic' } }, // TODO(批次7)
      { path: 'page', name: 'content:page', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '单页', permission: 'content:page' } }, // TODO(批次7)
      { path: 'form', name: 'content:form', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '表单', permission: 'content:form' } }, // TODO(批次7)
      { path: 'submission', name: 'content:submission', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '表单提交', permission: 'content:submission' } }, // TODO(批次7)
    ],
  },
]
