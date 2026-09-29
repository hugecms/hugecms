import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Modal, Timeline, Empty, message } from 'antd'
import { useCartStore } from '../../../stores/cartStore'
import styles from './order.module.css'

export const Route = createFileRoute('/_user/user/order')({
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
    status: 'completed',
    statusText: '交易成功 (已签收)',
    items: [
      {
        id: 2,
        title: 'Sony WH-1000XM5 高解析度无线降噪耳机 经典黑',
        price: 1899,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=120&q=80',
        sku: '经典黑 / 官方标配',
      },
    ],
    timeline: [
      {
        status: '已签收',
        time: '2026-09-21 16:45:00',
        desc: '已签收，签收人凭取件码取件。感谢在京东购物，期待再次光临！',
      },
      {
        status: '派送中',
        time: '2026-09-21 09:20:00',
        desc: '京东快递员已出发派送',
      },
      {
        status: '已出库',
        time: '2026-09-20 15:10:00',
        desc: '包裹已在京东北京分拣中心打包装箱',
      },
    ],
  },
]

function UserOrdersPage() {
  const [activeTab, setActiveTab] = useState<'all' | 'shipping' | 'comment'>('all')
  const [activeTimelineOrder, setActiveTimelineOrder] = useState<(typeof INITIAL_ORDERS)[0] | null>(null)
  const addItem = useCartStore((s) => s.addItem)

  const filteredOrders = INITIAL_ORDERS.filter((order) => {
    if (activeTab === 'all') return true
    if (activeTab === 'shipping') return order.status === 'shipping'
    if (activeTab === 'comment') return order.status === 'completed'
    return true
  })

  const handleRebuy = (item: (typeof INITIAL_ORDERS)[0]['items'][0]) => {
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
                  <span className={styles.shopName}>{order.shopName}</span>
                </div>
                <div className={styles.orderHeadRight}>
                  <span>收件人：{order.receiver}</span>
                </div>
              </div>

              <div className={styles.orderBodyRow}>
                {/* 商品单元 */}
                <div className={styles.orderGoodsCol}>
                  {order.items.map((item) => (
                    <div key={item.id} className={styles.orderGoodsItem}>
                      <img src={item.image} alt={item.title} />
                      <div className={styles.goodsInfo}>
                        <Link to="/item/$id" params={{ id: String(item.id) }}>
                          {item.title}
                        </Link>
                        <p>{item.sku}</p>
                      </div>
                      <div className={styles.goodsQty}>x{item.quantity}</div>
                    </div>
                  ))}
                </div>

                {/* 支付金额 */}
                <div className={styles.orderPriceCol}>
                  <div className={styles.priceNum}>¥{order.totalPrice.toFixed(2)}</div>
                  <div className={styles.payType}>在线支付</div>
                </div>

                {/* 状态与跟踪 */}
                <div className={styles.orderStatusCol}>
                  <div className={styles.statusTxt}>{order.statusText}</div>
                  <button
                    type="button"
                    className={styles.btnTrace}
                    onClick={() => setActiveTimelineOrder(order)}
                  >
                    查看物流进度
                  </button>
                </div>

                {/* 操作栏 */}
                <div className={styles.orderActionCol}>
                  {order.status === 'shipping' && (
                    <button
                      type="button"
                      className={styles.btnConfirm}
                      onClick={() => message.success('已确认收货，感谢您的支持！')}
                    >
                      确认收货
                    </button>
                  )}
                  {order.status === 'completed' && (
                    <button
                      type="button"
                      className={styles.btnConfirm}
                      onClick={() => message.info('评价晒单功能已开放')}
                    >
                      评价晒单
                    </button>
                  )}
                  <button
                    type="button"
                    className={styles.btnRebuy}
                    onClick={() => handleRebuy(order.items[0])}
                  >
                    再次购买
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* 物流轨迹弹窗 */}
      <Modal
        title={`订单 ${activeTimelineOrder?.orderId} - 京东自营物流实时追踪`}
        open={!!activeTimelineOrder}
        onCancel={() => setActiveTimelineOrder(null)}
        footer={null}
        width={560}
      >
        {activeTimelineOrder && (
          <div style={{ padding: '16px 0' }}>
            <Timeline
              items={activeTimelineOrder.timeline.map((t, idx) => ({
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
    </main>
  )
}

export default UserOrdersPage
