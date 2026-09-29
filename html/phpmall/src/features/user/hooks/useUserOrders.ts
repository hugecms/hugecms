import { useState, useMemo } from 'react'
import type { OrderDetail, OrderStatus } from '../../../types/order'

export const MOCK_USER_ORDERS: OrderDetail[] = [
  {
    orderId: 'JD20260928001',
    orderSn: '2847192837491',
    createTime: '2026-09-28 14:22:10',
    status: 'shipped',
    statusText: '卖家已发货',
    totalAmount: 7999,
    discountAmount: 100,
    freightAmount: 0,
    payAmount: 7899,
    address: {
      id: 'addr_1',
      name: '张三',
      phone: '138****8888',
      province: '北京市',
      city: '北京市',
      district: '大兴区',
      detail: '亦庄经济开发区科创十一街18号院',
      isDefault: true,
      tag: '公司',
    },
    items: [
      {
        id: 'item_1',
        goodsId: 1,
        goodsTitle: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
        goodsImage: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=200&q=80',
        price: 7999,
        quantity: 1,
        subtotal: 7999,
      },
    ],
  },
  {
    orderId: 'JD20260925002',
    orderSn: '2847192837492',
    createTime: '2026-09-25 09:15:32',
    status: 'completed',
    statusText: '已签收',
    totalAmount: 1899,
    discountAmount: 50,
    freightAmount: 0,
    payAmount: 1849,
    address: {
      id: 'addr_1',
      name: '张三',
      phone: '138****8888',
      province: '北京市',
      city: '北京市',
      district: '大兴区',
      detail: '亦庄经济开发区科创十一街18号院',
      isDefault: true,
      tag: '公司',
    },
    items: [
      {
        id: 'item_2',
        goodsId: 902,
        goodsTitle: 'Sony WH-1000XM5 无线降噪头戴耳机 黑色',
        goodsImage: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80',
        price: 1899,
        quantity: 1,
        subtotal: 1899,
      },
    ],
  },
]

export function useUserOrders() {
  const [orders, setOrders] = useState<OrderDetail[]>(MOCK_USER_ORDERS)
  const [activeTab, setActiveTab] = useState<OrderStatus | 'all'>('all')

  const filteredOrders = useMemo(() => {
    if (activeTab === 'all') return orders
    return orders.filter((o) => o.status === activeTab)
  }, [orders, activeTab])

  const cancelOrder = (orderId: string) => {
    setOrders((prev) =>
      prev.map((o) =>
        o.orderId === orderId ? { ...o, status: 'cancelled', statusText: '已取消' } : o
      )
    )
  }

  return {
    orders: filteredOrders,
    rawOrders: orders,
    activeTab,
    setActiveTab,
    cancelOrder,
  }
}

export default useUserOrders
