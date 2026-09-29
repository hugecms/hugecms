import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Modal, Timeline, Empty, message } from 'antd'
import { MallShortcutNav } from '../../features/mall/components/MallShortcutNav'
import { MallFooter } from '../../features/mall/components/MallFooter'
import { UserHeader } from '../../features/user/components/UserHeader'
import { UserSidebar } from '../../features/user/components/UserSidebar'
import { useCartStore } from '../../stores/cartStore'
import styles from './order.module.css'

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
    status: 'shipping',
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
      shopName: '京东自营官方旗舰店',
    })
    message.success(`已将「${item.title}」重新加入购物车！`)
  }

  return (
    <div className={styles.orderBody}>
      <div>
        <MallShortcutNav />
        <UserHeader title="我的京东 - 我的订单" />

        <div className={`w ${styles.orderLayout}`}>
          <UserSidebar />

          <main className={styles.orderMain}>
            <div className={styles.orderNavTabs}>
              <button
                type="button"
                className={`${styles.orderNavTab} ${activeTab === 'all' ? styles.orderNavTabActive : ''}`}
                onClick={() => setActiveTab('all')}
              >
                全部有效订单
              </button>
              <button
                type="button"
                className={`${styles.orderNavTab} ${activeTab === 'shipping' ? styles.orderNavTabActive : ''}`}
                onClick={() => setActiveTab('shipping')}
              >
                待收货 (1)
              </button>
              <button
                type="button"
                className={`${styles.orderNavTab} ${activeTab === 'comment' ? styles.orderNavTabActive : ''}`}
                onClick={() => setActiveTab('comment')}
              >
                待评价 (1)
              </button>
            </div>

            {filteredOrders.length === 0 ? (
              <Empty description="暂无符合条件的订单" />
            ) : (
              <div className={styles.orderList}>
                {filteredOrders.map((order) => (
                  <div key={order.orderId} className={styles.orderCard}>
                    <div className={styles.orderHead}>
                      <div className={styles.orderHeadLeft}>
                        <span>{order.createTime}</span>
                        <span>订单号：{order.orderId}</span>
                        <span style={{ color: '#005ea7' }}>{order.shopName}</span>
                      </div>
                      <Link to="/chat" style={{ color: '#666', textDecoration: 'none' }}>
                        💬 联系客服
                      </Link>
                    </div>

                    {order.items.map((item) => (
                      <div key={item.id} className={styles.orderBodyRow}>
                        <div className={styles.goodsCol}>
                          <img src={item.image} alt={item.title} className={styles.goodsImg} />
                          <div>
                            <Link to="/item/$id" params={{ id: String(item.id) }} className={styles.goodsTitle}>
                              {item.title}
                            </Link>
                            <div className={styles.goodsSku}>{item.sku}</div>
                          </div>
                        </div>

                        <div className={styles.priceCol}>
                          ¥{item.price.toFixed(2)} × {item.quantity}
                        </div>

                        <div className={styles.receiverCol}>
                          {order.receiver}
                        </div>

                        <div className={styles.statusCol}>
                          <span className={styles.statusText}>{order.statusText}</span>
                          <button
                            type="button"
                            className={styles.btnTrack}
                            onClick={() => setSelectedOrderForLogistics(order)}
                          >
                            查看物流轨迹
                          </button>
                        </div>

                        <div className={styles.actionCol}>
                          <button
                            type="button"
                            className={styles.btnRebuy}
                            onClick={() => handleRebuy(item)}
                          >
                            再次购买
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                ))}
              </div>
            )}
          </main>
        </div>
      </div>

      <Modal
        title={
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <span>🚚 京东自营物流实时轨迹</span>
            <span style={{ fontSize: 12, color: '#888' }}>
              订单号：{selectedOrderForLogistics?.orderId}
            </span>
          </div>
        }
        open={Boolean(selectedOrderForLogistics)}
        onCancel={() => setSelectedOrderForLogistics(null)}
        footer={null}
        width={560}
      >
        {selectedOrderForLogistics && (
          <div style={{ padding: '16px 8px 8px' }}>
            <Timeline
              items={selectedOrderForLogistics.timeline.map((t: any, idx: number) => ({
                color: idx === 0 ? 'red' : 'gray',
                children: (
                  <div>
                    <div style={{ fontWeight: idx === 0 ? 'bold' : 'normal', color: idx === 0 ? '#e1251b' : '#333' }}>
                      {t.status} - {t.desc}
                    </div>
                    <div style={{ fontSize: 11, color: '#999', marginTop: 2 }}>{t.time}</div>
                  </div>
                ),
              }))}
            />
          </div>
        )}
      </Modal>

      <MallFooter />
    </div>
  )
}
