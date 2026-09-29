import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import { Button, Space, message } from 'antd'
import { DownloadOutlined } from '@ant-design/icons'
import { DispatchTable } from '../../features/seller/components/DispatchTable'
import { exportOrdersExcel } from '../../utils/exportExcel'
import { useSellerStore } from '../../stores/sellerStore'

export const Route = createFileRoute('/seller/orders')({
  component: SellerOrdersPage,
})

function SellerOrdersPage() {
  const orders = useSellerStore((s) => s.orders)

  const handleExport = () => {
    exportOrdersExcel(orders, '商家履约订单列表')
    message.success('订单列表已成功导出为 Excel 文件')
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200">
        <div>
          <h2 className="text-base font-bold text-slate-800">订单履约与发货中心</h2>
          <p className="text-xs text-slate-500 mt-1">处理待履约订单、录入物流单号、快速批量发货</p>
        </div>
        <Space>
          <Button icon={<DownloadOutlined />} onClick={handleExport}>
            导出履约台账 (Excel)
          </Button>
        </Space>
      </div>

      <DispatchTable />
    </div>
  )
}

export default SellerOrdersPage
