import type { RouteRecordRaw } from 'vue-router'

/** 商品管理域路由 */
export const productRoutes: RouteRecordRaw[] = [
  {
    path: '/product',
    name: 'product',
    redirect: '/product/goods',
    meta: { title: '商品', icon: 'Goods' },
    children: [
      { path: 'goods', name: 'productGoods', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '商品列表', permission: 'product:goods' } }, // TODO(批次2)
      { path: 'category', name: 'productCategory', component: () => import('@/views/product/category/index.vue'), meta: { title: '商品分类', permission: 'product:category' } },
      { path: 'brand', name: 'productBrand', component: () => import('@/views/product/brand/index.vue'), meta: { title: '商品品牌', permission: 'product:brand' } },
      { path: 'type', name: 'productType', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '商品类型与属性', permission: 'product:type' } }, // TODO(批次2)
      { path: 'member-price', name: 'productMemberPrice', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '会员价', permission: 'product:memberPrice' } }, // TODO(批次2)
      { path: 'volume-price', name: 'productVolumePrice', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '阶梯价', permission: 'product:volumePrice' } }, // TODO(批次2)
      { path: 'review', name: 'productReview', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '商品评价', permission: 'product:review' } }, // TODO(批次2)
      { path: 'stock-log', name: 'productStockLog', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '库存流水', permission: 'product:stockLog' } }, // TODO(批次2)
      { path: 'inventory', name: 'productInventory', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '库存管理', permission: 'product:inventory' } }, // TODO(批次2)
      { path: 'stock-reminder', name: 'productStockReminder', component: () => import('@/views/PlaceholderView.vue'), meta: { title: '到货提醒', permission: 'product:stockReminder' } }, // TODO(批次2)
    ],
  },
]
