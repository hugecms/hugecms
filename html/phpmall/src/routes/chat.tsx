import React, { useState, useRef, useEffect } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { message } from 'antd'
import styles from './chat.module.css'

export const Route = createFileRoute('/chat')({
  component: CustomerChatPage,
})

interface ChatMessage {
  id: string
  sender: 'bot' | 'user'
  text: string
  time: string
  orderCard?: {
    orderId: string
    title: string
    price: number
    image: string
  }
}

function CustomerChatPage() {
  const [messages, setMessages] = useState<ChatMessage[]>([
    {
      id: '1',
      sender: 'bot',
      text: '您好，尊敬的张三 (PLUS会员)！我是京东智能在线助理 Joy 🐶。请问有什么可以帮您？您可以在下方直接提问，或选择快捷问题：',
      time: '刚刚',
    },
  ])
  const [inputVal, setInputVal] = useState('')
  const msgEndRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    msgEndRef.current?.scrollIntoView({ behavior: 'smooth' })
  }, [messages])

  const handleSend = (textToSend?: string) => {
    const content = (textToSend || inputVal).trim()
    if (!content) return

    const now = new Date()
    const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`

    const userMsg: ChatMessage = {
      id: String(Date.now()),
      sender: 'user',
      text: content,
      time: timeStr,
    }

    setMessages((prev) => [...prev, userMsg])
    if (!textToSend) setInputVal('')

    setTimeout(() => {
      let replyText = '收到您的问题，正在为您查询相关信息，请稍候...'
      if (content.includes('送达') || content.includes('物流')) {
        replyText =
          '您的订单 JD2026092800101 正在由【北京市朝阳区亚运村营业部】配送中，预计今日 14:00 前为您送达，请保持电话畅通！'
      } else if (content.includes('发票')) {
        replyText =
          '京东自营商品支持电子普通发票和专用增值税发票，您可在订单详情页点击「发票详情」直接下载 PDF。'
      } else if (content.includes('退货') || content.includes('售后')) {
        replyText =
          '京东支持 7 天无理由退货，由京东快递免费上门取件。您可在用户中心选择「返修退换货」发起申请。'
      } else if (content.includes('人工')) {
        replyText =
          '正在为您接通人工客服专员【工号 8042 - 小婷】，目前前方排队 1 人，预计等待时间 10 秒...'
      }

      setMessages((prev) => [
        ...prev,
        {
          id: String(Date.now() + 1),
          sender: 'bot',
          text: replyText,
          time: timeStr,
        },
      ])
    }, 600)
  }

  const handleSendOrder = () => {
    const userMsg: ChatMessage = {
      id: String(Date.now()),
      sender: 'user',
      text: '我想咨询这笔订单的配送情况：',
      time: '刚刚',
      orderCard: {
        orderId: 'JD2026092800101',
        title: 'Apple iPhone 16 Pro 256GB 原色钛金属',
        price: 7999,
        image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=80&q=80',
      },
    }
    setMessages((prev) => [...prev, userMsg])
    setTimeout(() => {
      setMessages((prev) => [
        ...prev,
        {
          id: String(Date.now() + 1),
          sender: 'bot',
          text: '已为您定位订单【JD2026092800101】。该订单状态为【运输中】，京东快递员正在加速配送中，预计将于今日 14:00 前送达！',
          time: '刚刚',
        },
      ])
    }, 500)
  }

  return (
    <div className={styles.chatBody}>
      {/* 1. 顶部 Header */}
      <header className={styles.chatHeaderBar}>
        <div className={styles.chatBrandTitle}>
          <div className={styles.botAvatar}>Joy</div>
          <div>
            <h3>京东智能客服机器人 (Joy)</h3>
          </div>
          <span className={styles.serviceStatus}>● 7×24小时专属服务中</span>
        </div>
        <div className={styles.chatTopLinks}>
          <Link to="/">🏠 返回商城首页</Link>
          <Link to="/user/order">📋 我的订单</Link>
          <Link to="/user">👤 个人中心</Link>
        </div>
      </header>

      {/* 2. IM 三栏工作台 */}
      <div className={styles.chatWorkbench}>
        {/* 左侧：关联近期订单 */}
        <aside className={styles.chatLeftSide}>
          <div className={styles.sideBlockHead}>📦 咨询关联订单</div>
          <div className={styles.recentOrderBox}>
            <div className={styles.orderMiniCard} onClick={handleSendOrder}>
              <div className={styles.omTop}>
                <span>订单号：JD2026092800101</span>
                <span className={styles.statusTransporting}>运输中</span>
              </div>
              <div className={styles.omBody}>
                <img
                  src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=80&q=80"
                  alt="iPhone 16 Pro"
                />
                <div className={styles.omInfo}>
                  <h5>Apple iPhone 16 Pro 256GB 原色钛金属</h5>
                  <div className={styles.omPrice}>¥7,999.00</div>
                </div>
              </div>
              <button type="button" className={styles.btnSendOrder}>
                发送此订单给客服咨询 ›
              </button>
            </div>
          </div>

          <div className={styles.sideBlockHead} style={{ marginTop: 10 }}>
            ⚡ 自助快捷服务通道
          </div>
          <div className={styles.quickServiceList}>
            <Link to="/user">• 申请退款 / 7天退货 ›</Link>
            <Link to="/user/order">• 实时物流轨迹时间轴 ›</Link>
            <Link to="/user">• 修改默认收货地址 ›</Link>
            <Link to="/coupon">• 领券中心与大额满减 ›</Link>
          </div>
        </aside>

        {/* 中间：核心聊天流 */}
        <main className={styles.chatMainArea}>
          {/* 消息列表 */}
          <div className={styles.chatMessageList}>
            {messages.map((m) => (
              <div
                key={m.id}
                className={`${styles.msgRow} ${m.sender === 'bot' ? styles.msgRowBot : styles.msgRowUser}`}
              >
                <div
                  className={`${styles.msgAvatar} ${m.sender === 'user' ? styles.msgAvatarUser : ''}`}
                >
                  {m.sender === 'bot' ? 'Joy' : '您'}
                </div>
                <div>
                  <div
                    className={`${styles.msgBubble} ${m.sender === 'user' ? styles.msgBubbleUser : ''}`}
                  >
                    <div>{m.text}</div>
                    {m.orderCard && (
                      <div
                        style={{
                          marginTop: 8,
                          padding: 8,
                          background: 'rgba(255,255,255,0.15)',
                          borderRadius: 4,
                          display: 'flex',
                          gap: 8,
                          alignItems: 'center',
                        }}
                      >
                        <img
                          src={m.orderCard.image}
                          alt={m.orderCard.title}
                          style={{ width: 40, height: 40, borderRadius: 4, objectFit: 'cover' }}
                        />
                        <div style={{ fontSize: 11 }}>
                          <div>{m.orderCard.title}</div>
                          <div style={{ fontWeight: 'bold' }}>¥{m.orderCard.price}</div>
                        </div>
                      </div>
                    )}
                  </div>
                  <div className={styles.msgTime}>{m.time}</div>
                </div>
              </div>
            ))}
            <div ref={msgEndRef} />
          </div>

          {/* 快捷问题药丸 */}
          <div className={styles.chatQuickPills}>
            <span
              className={styles.quickPill}
              onClick={() => handleSend('这笔订单什么时候能送达？')}
            >
              🚚 订单什么时候送达？
            </span>
            <span
              className={styles.quickPill}
              onClick={() => handleSend('如何申请开具增值税发票？')}
            >
              🧾 如何开具发票？
            </span>
            <span
              className={styles.quickPill}
              onClick={() => handleSend('不喜欢能支持七天无理由退货吗？')}
            >
              🔄 7天无理由退货流程
            </span>
            <span
              className={`${styles.quickPill} ${styles.quickPillDanger}`}
              onClick={() => handleSend('转接人工客服专员')}
            >
              👨‍💼 转人工客服
            </span>
          </div>

          {/* 底部输入框 */}
          <div className={styles.chatInputBox}>
            <textarea
              placeholder="输入您的问题，按回车或点击发送..."
              value={inputVal}
              onChange={(e) => setInputVal(e.target.value)}
              onKeyDown={(e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                  e.preventDefault()
                  handleSend()
                }
              }}
            />
            <div className={styles.chatBottomTools}>
              <div className={styles.toolIcons}>
                <span title="发送表情" onClick={() => setInputVal((prev) => prev + '😊')}>
                  😊
                </span>
                <span
                  title="上传图片"
                  onClick={() => message.info('已开启图片上传通道，请选择本地凭证截图')}
                >
                  🖼️
                </span>
                <span title="发送订单" onClick={handleSendOrder}>
                  📦
                </span>
              </div>
              <button type="button" onClick={() => handleSend()} className={styles.btnSendMsg}>
                发送
              </button>
            </div>
          </div>
        </main>

        {/* 右侧：常见 FAQ 与帮助 */}
        <aside className={styles.chatRightSide}>
          <div className={styles.faqCard}>
            <h4>❓ 热门常见问题</h4>
            <ul className={styles.faqList}>
              <li>
                <a href="javascript:;" onClick={() => handleSend('为什么我的订单还没有发货？')}>
                  • 订单已支付为何未发货？
                </a>
              </li>
              <li>
                <a href="javascript:;" onClick={() => handleSend('京豆如何抵扣现金？')}>
                  • 购物返还的京豆怎么使用？
                </a>
              </li>
              <li>
                <a href="javascript:;" onClick={() => handleSend('支持换货或保修吗？')}>
                  • 商品出现质量故障如何保修？
                </a>
              </li>
              <li>
                <a href="javascript:;" onClick={() => handleSend('价保申请规则是什么？')}>
                  • 购买后降价如何申请一键价保？
                </a>
              </li>
            </ul>
          </div>

          <div className={styles.faqCard}>
            <h4>📞 专属热线服务</h4>
            <p style={{ fontSize: 12, color: '#666', lineHeight: 1.6, margin: 0 }}>
              PLUS 会员服务专线：<strong style={{ color: '#e1251b' }}>950618</strong>
              <br />
              企业采购专属通道：<strong style={{ color: '#005ea7' }}>400-606-5500</strong>
            </p>
          </div>
        </aside>
      </div>
    </div>
  )
}
