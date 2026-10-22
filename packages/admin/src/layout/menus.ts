import type { RouteRecordRaw } from 'vue-router'

export interface MenuLeaf {
  path: string
  title: string
}

export interface MenuGroup {
  path: string
  title: string
  icon: string
  children: MenuLeaf[]
}

/**
 * 从路由表派生两级菜单（路由表为单一事实源）：
 * 遍历 Layout 记录的一层子路由（跳过 meta.hidden），
 * 有 children 的作为菜单组（el-sub-menu），无 children 的作为直挂叶子（el-menu-item）
 */
export function buildMenus(routes: readonly RouteRecordRaw[]): MenuGroup[] {
  const layout = routes.find((r) => r.name === 'layout')
  const menus: MenuGroup[] = []

  for (const child of layout?.children ?? []) {
    if (child.meta?.hidden) {
      continue
    }
    const groupPath = `/${String(child.path).replace(/^\//, '')}`
    const leaves: MenuLeaf[] = (child.children ?? [])
      .filter((leaf) => !leaf.meta?.hidden)
      .map((leaf) => ({
        path: `${groupPath}/${String(leaf.path).replace(/^\//, '')}`,
        title: String(leaf.meta?.title ?? ''),
      }))
    menus.push({
      path: groupPath,
      title: String(child.meta?.title ?? ''),
      icon: String(child.meta?.icon ?? 'Menu'),
      children: leaves,
    })
  }

  return menus
}
