import React from 'react'
import { Link, Outlet } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'

interface TradeLayoutProps {
  title?: string
  showSearch?: boolean
  step?: 1 | 2 | 3
  children?: React.ReactNode
}

export const TradeLayout: React.FC<TradeLayoutProps> = ({
  title = '购物车',
  showSearch = true,
  children,
}) => {
  return (
    <div className="trade-layout min-h-screen flex flex-col bg-[#f4f4f4]">
      {/* 顶部快捷导航 */}
      <MallShortcutNav />

      {/* 交易专属Header */}
      <div className="w header-trade" style={{ padding: '20px 0', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
          <Link to="/" style={{ textDecoration: 'none' }}>
            <div style={{
              width: '100px',
              height: '36px',
              background: '#e1251b',
              borderRadius: '4px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#fff',
              fontSize: '20px',
              fontWeight: 900,
            }}>
              JD
            </div>
          </Link>
          <span style={{ fontSize: '20px', color: '#333', fontWeight: 'bold' }}>{title}</span>
        </div>

        {showSearch && (
          <div style={{ display: 'flex', width: '320px', height: '34px' }}>
            <input
              type="text"
              placeholder="自营好物 爆款秒杀"
              style={{
                flex: 1,
                border: '2px solid #e1251b',
                borderRight: 'none',
                padding: '0 12px',
                fontSize: '12px',
              }}
            />
            <button style={{
              background: '#e1251b',
              color: '#fff',
              border: 'none',
              padding: '0 18px',
              cursor: 'pointer',
              fontSize: '13px',
            }}>
              搜索
            </button>
          </div>
        )}
      </div>

      {/* 交易主体内容 */}
      <main className="flex-1 w-full">
        {children || <Outlet />}
      </main>

      {/* 经典页脚 */}
      <MallFooter />
    </div>
  )
}

export default TradeLayout
