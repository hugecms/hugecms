import React from 'react'
import { Link, Outlet } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'

export const UserLayout: React.FC<{ children?: React.ReactNode }> = ({ children }) => {
  return (
    <div className="user-layout min-h-screen flex flex-col bg-[#f4f4f4]">
      {/* 顶部快捷条 */}
      <MallShortcutNav />

      {/* 我的京东专属二级头部 */}
      <div style={{ background: '#e2231a', color: '#fff', height: '44px' }}>
        <div className="w" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', height: '100%' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '30px' }}>
            <span style={{ fontSize: '18px', fontWeight: 'bold' }}>我的京东</span>
            <div style={{ display: 'flex', gap: '20px', fontSize: '14px' }}>
              <Link to="/user" style={{ color: '#fff' }}>首页</Link>
              <Link to="/user/order" style={{ color: '#fff' }}>订单中心</Link>
              <Link to="/plus" style={{ color: '#fff' }}>PLUS会员专区</Link>
            </div>
          </div>
          <Link to="/" style={{ color: '#fff', fontSize: '12px' }}>返回商城首页 ›</Link>
        </div>
      </div>

      {/* 主体两栏布局 */}
      <div className="w" style={{ display: 'flex', gap: '20px', padding: '20px 0', flex: 1 }}>
        {/* 左侧菜单 */}
        <aside style={{ width: '160px', background: '#fff', border: '1px solid #e5e5e5', borderRadius: '4px', padding: '16px 0', height: 'fit-content' }}>
          <div style={{ padding: '0 16px 10px', fontWeight: 'bold', borderBottom: '1px solid #f0f0f0', marginBottom: '8px' }}>
            订单中心
          </div>
          <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
            <li>
              <Link to="/user/order" style={{ display: 'block', padding: '8px 16px', color: '#333', fontSize: '13px' }}>
                我的订单
              </Link>
            </li>
            <li>
              <a href="javascript:;" style={{ display: 'block', padding: '8px 16px', color: '#666', fontSize: '13px' }}>
                评价晒单
              </a>
            </li>
          </ul>

          <div style={{ padding: '16px 16px 10px', fontWeight: 'bold', borderBottom: '1px solid #f0f0f0', marginBottom: '8px' }}>
            关注中心
          </div>
          <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
            <li>
              <a href="javascript:;" style={{ display: 'block', padding: '8px 16px', color: '#666', fontSize: '13px' }}>
                关注的商品
              </a>
            </li>
            <li>
              <a href="javascript:;" style={{ display: 'block', padding: '8px 16px', color: '#666', fontSize: '13px' }}>
                关注的店铺
              </a>
            </li>
          </ul>

          <div style={{ padding: '16px 16px 10px', fontWeight: 'bold', borderBottom: '1px solid #f0f0f0', marginBottom: '8px' }}>
            客户服务
          </div>
          <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
            <li>
              <Link to="/chat" style={{ display: 'block', padding: '8px 16px', color: '#666', fontSize: '13px' }}>
                在线客服
              </Link>
            </li>
            <li>
              <a href="javascript:;" style={{ display: 'block', padding: '8px 16px', color: '#666', fontSize: '13px' }}>
                返修退换货
              </a>
            </li>
          </ul>
        </aside>

        {/* 右侧主工作区 */}
        <main style={{ flex: 1, minWidth: 0 }}>
          {children || <Outlet />}
        </main>
      </div>

      {/* 底部页脚 */}
      <MallFooter />
    </div>
  )
}

export default UserLayout
