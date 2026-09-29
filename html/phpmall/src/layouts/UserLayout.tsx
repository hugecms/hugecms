import React from 'react'
import { Outlet } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'
import { UserHeader } from '../features/user/components/UserHeader'
import { UserSidebar } from '../features/user/components/UserSidebar'

export const UserLayout: React.FC<{ children?: React.ReactNode }> = ({ children }) => {
  return (
    <div className="user-layout min-h-screen flex flex-col bg-[#f4f4f4]">
      {/* 顶部快捷条 */}
      <MallShortcutNav />

      {/* 用户中心统一二级头部 */}
      <UserHeader />

      {/* 主体两栏布局：左侧 208px 侧边栏 + 右侧主内容 */}
      <div className="w flex gap-5 py-5 flex-1 items-start">
        <UserSidebar />
        <main className="flex-1 min-w-0">
          {children || <Outlet />}
        </main>
      </div>

      {/* 底部页脚 */}
      <MallFooter />
    </div>
  )
}

export default UserLayout
