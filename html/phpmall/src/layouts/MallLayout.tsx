import React from 'react'
import { Outlet } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallHeader } from '../features/mall/components/MallHeader'
import { MallFooter } from '../features/mall/components/MallFooter'

export const MallLayout: React.FC<{ children?: React.ReactNode }> = ({ children }) => {
  return (
    <div className="mall-layout-root min-h-screen flex flex-col bg-[#f4f4f4]">
      {/* 顶部快捷导航条 */}
      <MallShortcutNav />

      {/* 头部搜索与频道栏 */}
      <MallHeader />

      {/* 主体页面内容挂载点 */}
      <main className="flex-1 w-full">
        {children || <Outlet />}
      </main>

      {/* 底部四徽标与帮助版权 */}
      <MallFooter />
    </div>
  )
}

export default MallLayout
