import { describe, expect, it } from 'vitest'
import type { RouteRecordRaw } from 'vue-router'
import { buildMenus } from '@/layout/menus'

function group(path: string, title: string, icon: string, children: RouteRecordRaw[] = []): RouteRecordRaw {
  return { path, name: path, meta: { title, icon }, children }
}

function leaf(path: string, title: string): RouteRecordRaw {
  return { path, name: path, component: () => Promise.resolve({}), meta: { title } }
}

const layout = (children: RouteRecordRaw[]): RouteRecordRaw[] => [
  { path: '/login', name: 'login', component: () => Promise.resolve({}), meta: { title: '登录' } },
  { path: '/', name: 'layout', redirect: '/dashboard', children },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => Promise.resolve({}), meta: { title: '404' } },
]

describe('buildMenus', () => {
  it('从 Layout 记录派生菜单，忽略 login 与 404', () => {
    const routes = layout([leaf('dashboard', '仪表盘')])

    const menus = buildMenus(routes)

    expect(menus).toHaveLength(1)
    expect(menus[0]).toMatchObject({ path: '/dashboard', title: '仪表盘', children: [] })
  })

  it('有 children 的一级记录派生为菜单组，叶子路径为组路径拼接', () => {
    const routes = layout([
      group('/product', '商品', 'Goods', [
        leaf('brand', '品牌管理'),
        leaf('category', '分类管理'),
      ]),
    ])

    const menus = buildMenus(routes)

    expect(menus[0]!.icon).toBe('Goods')
    expect(menus[0]!.children).toEqual([
      { path: '/product/brand', title: '品牌管理' },
      { path: '/product/category', title: '分类管理' },
    ])
  })

  it('hidden 的一级组与二级叶子不进菜单', () => {
    const hiddenLeaf: RouteRecordRaw = { path: 'cart', name: 'cart', component: () => Promise.resolve({}), meta: { title: '购物车', hidden: true } }
    const routes = layout([
      group('/order', '订单', 'Tickets', [leaf('list', '订单列表'), hiddenLeaf]),
      { path: '/secret', name: 'secret', meta: { title: '隐藏组', hidden: true }, children: [leaf('x', 'x')] },
    ])

    const menus = buildMenus(routes)

    expect(menus).toHaveLength(1)
    expect(menus[0]!.children).toHaveLength(1)
    expect(menus[0]!.children[0]!.title).toBe('订单列表')
  })

  it('空路由表返回空菜单', () => {
    expect(buildMenus([])).toEqual([])
  })
})
