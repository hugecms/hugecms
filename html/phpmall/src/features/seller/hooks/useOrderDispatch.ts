import { useState, useMemo } from 'react'
import { message } from 'antd'
import { useSellerStore } from '../../../stores/sellerStore'

export function useOrderDispatch() {
  const { orders, markOrderShipped } = useSellerStore()
  const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([])
  const [filterStatus, setFilterStatus] = useState<'all' | 'pending' | 'shipped'>('all')

  const filteredOrders = useMemo(() => {
    if (filterStatus === 'all') return orders
    return orders.filter((o) => o.status === filterStatus)
  }, [orders, filterStatus])

  const handleShip = (sn: string) => {
    markOrderShipped(sn)
    message.success(`订单 ${sn} 已成功发货并生成顺丰速运单号！`)
  }

  const handleBatchShip = () => {
    if (selectedRowKeys.length === 0) {
      message.warning('请勾选要批量发货的订单')
      return
    }
    selectedRowKeys.forEach((key) => {
      markOrderShipped(String(key))
    })
    message.success(`已批量履约发货 ${selectedRowKeys.length} 笔订单`)
    setSelectedRowKeys([])
  }

  return {
    orders: filteredOrders,
    rawOrders: orders,
    selectedRowKeys,
    setSelectedRowKeys,
    filterStatus,
    setFilterStatus,
    handleShip,
    handleBatchShip,
  }
}

export default useOrderDispatch
