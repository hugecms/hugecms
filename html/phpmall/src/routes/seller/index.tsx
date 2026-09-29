import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import { MetricsCards } from '../../features/seller/components/MetricsCards'
import { TodoAlerts } from '../../features/seller/components/TodoAlerts'
import { DispatchTable } from '../../features/seller/components/DispatchTable'
import { InventoryTable } from '../../features/seller/components/InventoryTable'

export const Route = createFileRoute('/seller/')({
  component: SellerDashboardPage,
})

function SellerDashboardPage() {
  return (
    <div className="space-y-4">
      {/* 1. 经营指标四宫格 */}
      <MetricsCards />

      {/* 2. 待办事项告警横幅 */}
      <TodoAlerts />

      {/* 3. 待发货订单履约中心 */}
      <DispatchTable />

      {/* 4. 热销商品与库存预警 */}
      <InventoryTable />
    </div>
  )
}
