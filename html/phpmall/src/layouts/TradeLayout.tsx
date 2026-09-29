import React from 'react'
import { Outlet, useRouterState } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'
import { TradeHeader } from '../features/trade/components/TradeHeader'

interface TradeLayoutProps {
  title?: string
  showSearch?: boolean
  step?: 1 | 2 | 3
  children?: React.ReactNode
}

export const TradeLayout: React.FC<TradeLayoutProps> = ({
  children,
}) => {
  const routerState = useRouterState()
  const pathname = routerState.location.pathname

  let type: 'cart' | 'checkout' | 'pay' = 'cart'
  let step = 1

  if (pathname.includes('/pay')) {
    type = 'pay'
    step = 3
  } else if (pathname.includes('/checkout')) {
    type = 'checkout'
    step = 2
  } else {
    type = 'cart'
    step = 1
  }

  return (
    <div className="trade-layout min-h-screen flex flex-col bg-[#f4f4f4]">
      {/* 顶部快捷导航 */}
      <MallShortcutNav />

      {/* 交易专属统一 Header */}
      <TradeHeader type={type} currentStep={step} />

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
