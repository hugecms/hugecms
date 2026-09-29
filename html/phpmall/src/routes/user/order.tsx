import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Tabs, Tag, Button, Modal, Timeline, Empty, message } from 'antd'
import {
  CarOutlined,
  CheckCircleOutlined,
  ClockCircleOutlined,
  ShoppingOutlined,
} from '@ant-design/icons'
import { MallShortcutNav } from '../../features/mall/components/MallShortcutNav'
import { MallFooter } from '../../features/mall/components/MallFooter'
import { UserHeader } from '../../features/user/components/UserHeader'
import { UserSidebar } from '../../features/user/components/UserSidebar'
import { useCartStore } from '../../stores/cartStore'

export const Route = createFileRoute('/user/order')({
  component: UserOrdersPage,
})

const INITIAL_ORDERS = [
  {
    orderId: 'JD2026092800101',
    createTime: '2026-09-28 14:32:05',
    shopName: 'Apple产品京东自营旗舰店',
    receiver: '张三 (138****0001)',
    totalPrice: 7999,
    status: 'shipping', // shipping, completed, unpaid
    statusText: '京东快递 运输中',
    items: [
      {
        id: 1,
        title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
        price: 7999,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=120&q=80',
        sku: '原色钛金属 / 256GB',
      },
    ],
    timeline: [
      {
        status: '运输中',
        time: '2026-09-29 08:30:15',
        desc: '【北京市朝阳区亚运村营业部】快递员已出发，预计今日 14:00 前送达',
      },
      {
        status: '分拣完成',
        time: '2026-09-29 04:12:00',
        desc: '北京顺义智能转运中心 已发出',
      },
      {
        status: '出库',
        time: '2026-09-28 18:20:45',
        desc: '京东华北大区自动化立体总库 已打包装箱出库',
      },
      {
        status: '下单成功',
        time: '2026-09-28 14:32:05',
        desc: '客户在线支付成功，系统正在安排配货',
      },
    ],
  },
  {
    orderId: 'JD2026092000889',
    createTime: '2026-09-20 10:15:30',
    shopName: '索尼音频京东自营旗舰店',
    receiver: '张三 (138****0001)',
    totalPrice: 1899,
    status: 'comment',
    statusText: '已签收 (待评价)',
    items: [
      {
        id: 2,
        title: 'Sony WH-1000XM5 高解析度无线降噪头戴耳机 黑色',
        price: 1899,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=120&q=80',
        sku: '经典黑色 / 官方标配',
      },
    ],
    timeline: [
      {
        status: '已签收',
        time: '2026-09-21 11:20:00',
        desc: '已由本人签收，感谢在京东商城购物！',
      },
    ],
  },
]

function UserOrdersPage() {
  const [activeTab, setActiveTab] = useState('all')
  const [selectedOrderForLogistics, setSelectedOrderForLogistics] = useState<any | null>(null)
  const addItem = useCartStore((state) => state.addItem)

  const filteredOrders = INITIAL_ORDERS.filter((order) => {
    if (activeTab === 'all') return true
    return order.status === activeTab
  })

  const handleRebuy = (item: any) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: item.sku,
      shopName: '京东自营旗舰店',
    })
    message.success(`已将「${item.title}」重新加入购物车！`)
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        <MallShortcutNav />
        <UserHeader title="订单中心 - 我的订单" />

        <div className="max-w-7xl mx-auto px-4 py-6 flex gap-6">
          <UserSidebar />

          <main className="flex-1 bg-white rounded-xl border border-slate-200/80 shadow-xs p-6">
            <h3 className="text-base font-bold text-slate-800 mb-4">订单中心</h3>

            <Tabs
              activeKey={activeTab}
              onChange={setActiveTab}
              items={[
                { key: 'all', label: `全部订单 (${INITIAL_ORDERS.length})` },
                { key: 'unpaid', label: '待付款 (0)' },
                { key: 'shipping', label: '待收货 (1)' },
                { key: 'comment', label: '待评价 (1)' },
              ]}
            />

            {/* 订单表格表头 */}
            <div className="bg-slate-50 border border-slate-200 rounded-lg p-3 grid grid-cols-12 text-xs font-semibold text-slate-500 mb-4 text-center">
              <span className="col-span-5 text-left pl-2">订单商品详情</span>
              <span className="col-span-2">收货人</span>
              <span className="col-span-2">订单金额</span>
              <span className="col-span-1">状态</span>
              <span className="col-span-2">操作</span>
            </div>

            {/* 订单列表 */}
            {filteredOrders.length === 0 ? (
              <div className="py-12 text-center">
                <Empty description="暂无符合条件的订单" />
              </div>
            ) : (
              <div className="space-y-4">
                {filteredOrders.map((order) => (
                  <div
                    key={order.orderId}
                    className="border border-slate-200 rounded-xl overflow-hidden shadow-2xs hover:border-slate-300 transition"
                  >
                    {/* 订单头部条 */}
                    <div className="bg-slate-50/80 px-4 py-2.5 text-xs text-slate-500 flex items-center justify-between border-b border-slate-200">
                      <div className="flex items-center gap-4">
                        <span className="font-semibold text-slate-800">
                          {order.createTime}
                        </span>
                        <span>订单号：{order.orderId}</span>
                        <span className="text-slate-700 font-medium">{order.shopName}</span>
                      </div>
                      <Link to="/chat" className="text-rose-600 hover:underline">
                        联系客服
                      </Link>
                    </div>

                    {/* 订单主内容网格 */}
                    <div className="p-4 grid grid-cols-12 items-center text-xs">
                      {/* 商品列 */}
                      <div className="col-span-5 space-y-3">
                        {order.items.map((it) => (
                          <div key={it.id} className="flex gap-3 items-center">
                            <img
                              src={it.image}
                              alt={it.title}
                              className="w-14 h-14 object-cover rounded-lg border border-slate-200 shrink-0"
                            />
                            <div>
                              <Link
                                to="/item/$id"
                                params={{ id: String(it.id) }}
                                className="font-medium text-slate-800 hover:text-rose-600 line-clamp-1"
                              >
                                {it.title}
                              </Link>
                              <p className="text-slate-400 mt-0.5">{it.sku}</p>
                              <span className="text-rose-600 font-semibold">
                                ¥{it.price.toFixed(2)} x {it.quantity}
                              </span>
                            </div>
                          </div>
                        ))}
                      </div>

                      {/* 收货人 */}
                      <div className="col-span-2 text-center text-slate-700">
                        {order.receiver}
                      </div>

                      {/* 总额 */}
                      <div className="col-span-2 text-center">
                        <span className="text-sm font-bold text-slate-900 block">
                          ¥{order.totalPrice.toFixed(2)}
                        </span>
                        <span className="text-[11px] text-slate-400">在线支付</span>
                      </div>

                      {/* 履约状态 */}
                      <div className="col-span-1 text-center">
                        <Tag
                          color={
                            order.status === 'shipping'
                              ? 'processing'
                              : order.status === 'comment'
                                ? 'success'
                                : 'default'
                          }
                        >
                          {order.statusText}
                        </Tag>
                      </div>

                      {/* 操作 */}
                      <div className="col-span-2 flex flex-col items-center gap-2">
                        {order.status === 'shipping' && (
                          <Button
                            size="small"
                            type="primary"
                            danger
                            onClick={() => setSelectedOrderForLogistics(order)}
                          >
                            跟踪物流
                          </Button>
                        )}
                        {order.status === 'comment' && (
                          <Button size="small" type="primary" danger ghost>
                            立即评价
                          </Button>
                        )}
                        <Button
                          size="small"
                          onClick={() => handleRebuy(order.items[0])}
                        >
                          再次购买
                        </Button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </main>
        </div>
      </div>

      {/* 物流轨迹弹窗 */}
      <Modal
        title={
          <div className="flex items-center gap-2 text-rose-600 font-bold">
            <CarOutlined />
            <span>京东物流轨迹时间轴 (订单号: {selectedOrderForLogistics?.orderId})</span>
          </div>
        }
        open={!!selectedOrderForLogistics}
        onCancel={() => setSelectedOrderForLogistics(null)}
        footer={[
          <Button key="close" onClick={() => setSelectedOrderForLogistics(null)}>
            关闭
          </Button>,
        ]}
      >
        <div className="py-4">
          <Timeline
            items={
              selectedOrderForLogistics?.timeline.map((t: any, idx: number) => ({
                color: idx === 0 ? 'red' : 'gray',
                children: (
                  <div>
                    <div className="font-bold text-xs text-slate-800">{t.desc}</div>
                    <div className="text-[11px] text-slate-400 mt-1">{t.time}</div>
                  </div>
                ),
              })) || []
            }
          />
        </div>
      </Modal>

      <MallFooter />
    </div>
  )
}
