import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import { Button, Space } from 'antd'
import { PlusOutlined } from '@ant-design/icons'
import { InventoryTable } from '../../features/seller/components/InventoryTable'
import { useSellerStore } from '../../stores/sellerStore'

export const Route = createFileRoute('/seller/goods')({
  component: SellerGoodsPage,
})

function SellerGoodsPage() {
  const openAddGoodsModal = useSellerStore((s) => s.openAddGoodsModal)

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200">
        <div>
          <h2 className="text-base font-bold text-slate-800">商品库存管理</h2>
          <p className="text-xs text-slate-500 mt-1">管理店铺所有在售商品、监控库存预警与今日销量</p>
        </div>
        <Space>
          <Button
            type="primary"
            danger
            icon={<PlusOutlined />}
            onClick={openAddGoodsModal}
          >
            发布新商品
          </Button>
        </Space>
      </div>

      <InventoryTable />
    </div>
  )
}

export default SellerGoodsPage
