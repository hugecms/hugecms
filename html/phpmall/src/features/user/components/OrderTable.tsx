import React from 'react'
import { Link } from '@tanstack/react-router'
import { Table, Tag, Button, Empty } from 'antd'
import type { ColumnsType } from 'antd/es/table'
import type { OrderDetail } from '../../../types/order'

export interface OrderTableProps {
  orders: OrderDetail[]
  loading?: boolean
  onPay?: (order: OrderDetail) => void
  onCancel?: (order: OrderDetail) => void
}

export const OrderTable: React.FC<OrderTableProps> = ({
  orders,
  loading = false,
  onPay,
  onCancel,
}) => {
  const columns: ColumnsType<OrderDetail> = [
    {
      title: '订单详情',
      dataIndex: 'orderSn',
      key: 'orderSn',
      render: (_, record) => (
        <div>
          <div className="text-xs text-slate-500 mb-2">
            <span>订单号: {record.orderSn}</span>
            <span className="ml-4">下单时间: {record.createTime}</span>
          </div>
          <div className="space-y-2">
            {record.items.map((item) => (
              <div key={item.id} className="flex items-center gap-3">
                <img
                  src={item.goodsImage}
                  alt={item.goodsTitle}
                  className="w-12 h-12 rounded object-cover border border-slate-100 shrink-0"
                />
                <div className="flex-1 min-w-0">
                  <div className="text-xs text-slate-700 truncate">{item.goodsTitle}</div>
                  <div className="text-[11px] text-slate-400">
                    ¥{item.price} × {item.quantity}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      ),
    },
    {
      title: '收件人',
      dataIndex: 'address',
      key: 'address',
      width: 120,
      render: (addr) => (
        <div className="text-xs text-slate-600">
          <div>{addr?.name}</div>
          <div className="text-slate-400 text-[11px]">{addr?.phone}</div>
        </div>
      ),
    },
    {
      title: '金额',
      dataIndex: 'payAmount',
      key: 'payAmount',
      width: 110,
      render: (val) => (
        <div className="text-xs">
          <div className="font-bold text-slate-800">¥{val}</div>
          <div className="text-[11px] text-slate-400">在线支付</div>
        </div>
      ),
    },
    {
      title: '状态',
      dataIndex: 'status',
      key: 'status',
      width: 100,
      render: (status: string, record) => {
        const colorMap: Record<string, string> = {
          unpaid: 'orange',
          paid: 'blue',
          shipped: 'cyan',
          completed: 'green',
          cancelled: 'default',
        }
        return <Tag color={colorMap[status] || 'default'}>{record.statusText}</Tag>
      },
    },
    {
      title: '操作',
      key: 'action',
      width: 120,
      render: (_, record) => (
        <div className="space-y-1">
          {record.status === 'unpaid' && (
            <Button
              type="primary"
              danger
              size="small"
              onClick={() => onPay?.(record)}
              className="w-full text-xs"
            >
              立即付款
            </Button>
          )}
          {record.status === 'unpaid' && (
            <Button
              type="text"
              size="small"
              onClick={() => onCancel?.(record)}
              className="w-full text-xs text-slate-500"
            >
              取消订单
            </Button>
          )}
          <Link
            to="/user/order"
            className="block text-center text-xs text-slate-600 hover:text-red-600"
          >
            查看详情
          </Link>
        </div>
      ),
    },
  ]

  return (
    <div className="bg-white rounded-lg border border-slate-200 overflow-hidden">
      <Table
        rowKey="orderId"
        columns={columns}
        dataSource={orders}
        loading={loading}
        pagination={{ pageSize: 5 }}
        locale={{
          emptyText: <Empty description="暂无符合条件的订单记录" />,
        }}
      />
    </div>
  )
}

export default OrderTable
