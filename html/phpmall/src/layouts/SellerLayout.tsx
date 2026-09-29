import React from 'react'
import { Outlet } from '@tanstack/react-router'
import { SellerHeader } from '../features/seller/components/SellerHeader'
import { SellerSider } from '../features/seller/components/SellerSider'
import { AddGoodsModal } from '../features/seller/components/AddGoodsModal'

export const SellerLayout: React.FC = () => {
  return (
    <div className="min-h-screen bg-[#f5f5f5]">
      {/* 顶部 Header (保持原有 #1e293b 深色背景) */}
      <SellerHeader />

      {/* 两栏主体布局 */}
      <div className="flex min-h-[calc(100vh-56px)]">
        {/* 左侧 Ant Design Sider 侧边栏 */}
        <SellerSider />

        {/* 右侧动态主页面 */}
        <main className="flex-1 overflow-y-auto p-6">
          <Outlet />
        </main>
      </div>

      {/* 全局发布商品 Modal 弹窗 */}
      <AddGoodsModal />
    </div>
  )
}
