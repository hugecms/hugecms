import 'vue-router'

declare module 'vue-router' {
  interface RouteMeta {
    /** 页面标题（document.title 与菜单文案） */
    title?: string
    /** 一级菜单组图标名（见 layout/components/icons.ts 的 menuIcons），二级叶子不配 */
    icon?: string
    /** 权限点（admin_permissions.code 命名空间，V1 预留不校验） */
    permission?: string
    /** 不参与菜单派生（保留路由不上菜单） */
    hidden?: boolean
  }
}
