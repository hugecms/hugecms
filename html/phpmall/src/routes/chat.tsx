import React, { useState, useRef, useEffect } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Avatar, Input, Button, Tag, Card, message } from 'antd'
import {
  RobotOutlined,
  UserOutlined,
  SendOutlined,
  HomeOutlined,
  ShoppingOutlined,
  QuestionCircleOutlined,
  CustomerServiceOutlined,
} from '@ant-design/icons'

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
      text: '您好，尊敬的张三 (PLUS会员)！我是京东智能在线助理 Joy 🐶。请问有什么可以帮您？您可以在下方直接提问，或选择快捷问题。',
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

    // 智能机器人模拟回复
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
        image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=100&q=80',
      },
    }
    setMessages((prev) => [...prev, userMsg])

    setTimeout(() => {
      setMessages((prev) => [
        ...prev,
        {
          id: String(Date.now() + 1),
          sender: 'bot',
          text: '已为您锁定订单 JD2026092800101。该订单已出库并在派送途中，如需修改收货时间请随时告诉我。',
          time: '刚刚',
        },
      ])
    }, 600)
  }

  const quickQuestions = [
    '🚚 这笔订单什么时候能送达？',
    '🧾 如何申请开具增值税发票？',
    '🔄 7天无理由退货流程',
    '👨‍💼 转接人工客服专员',
  ]

  return (
    <div className="h-screen flex flex-col bg-slate-100">
      {/* 顶部客服 Header */}
      <header className="bg-slate-900 text-white px-6 py-3.5 flex items-center justify-between shrink-0 shadow-md">
        <div className="flex items-center gap-3">
          <Avatar size={36} className="bg-rose-600 font-bold">
            Joy
          </Avatar>
          <div>
            <div className="font-bold text-sm flex items-center gap-2">
              <span>京东智能客服机器人 (Joy)</span>
              <span className="text-[10px] text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded-full font-normal">
                ● 7×24小时专属服务中
              </span>
            </div>
            <div className="text-[11px] text-slate-400">解答购物、物流、发票与售后问题</div>
          </div>
        </div>

        <div className="flex items-center gap-4 text-xs">
          <Link to="/" className="text-slate-300 hover:text-white flex items-center gap-1">
            <HomeOutlined /> 返回商城首页
          </Link>
          <Link to="/user/order" className="text-slate-300 hover:text-white flex items-center gap-1">
            <ShoppingOutlined /> 我的订单
          </Link>
        </div>
      </header>

      {/* 客服工作台三栏主体 */}
      <div className="flex-1 max-w-7xl w-full mx-auto p-4 flex gap-4 overflow-hidden">
        {/* 左侧：关联订单与快捷入口 */}
        <aside className="w-72 bg-white rounded-xl border border-slate-200/80 shadow-xs p-4 flex flex-col justify-between shrink-0">
          <div>
            <h4 className="text-xs font-bold text-slate-700 pb-2 border-b border-slate-100 flex items-center gap-1">
              📦 咨询关联订单
            </h4>

            <div className="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-700">JD2026092800101</span>
                <Tag color="processing">运输中</Tag>
              </div>
              <div className="flex gap-2 items-center">
                <img
                  src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=80&q=80"
                  alt="goods"
                  className="w-12 h-12 rounded object-cover border border-slate-200"
                />
                <div className="flex-1 min-w-0">
                  <p className="truncate font-medium text-slate-800">
                    Apple iPhone 16 Pro 256GB
                  </p>
                  <p className="text-rose-600 font-bold mt-1">¥7,999.00</p>
                </div>
              </div>
              <button
                type="button"
                onClick={handleSendOrder}
                className="mt-3 w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded transition text-center cursor-pointer"
              >
                发送此订单给客服咨询 &gt;
              </button>
            </div>
          </div>

          <div className="pt-4 border-t border-slate-100 space-y-2 text-xs">
            <div className="text-[11px] font-bold text-slate-400">⚡ 自助快捷服务</div>
            <Link to="/user/order" className="block text-slate-600 hover:text-rose-600">
              • 实时物流轨迹时间轴 &gt;
            </Link>
            <Link to="/user" className="block text-slate-600 hover:text-rose-600">
              • 修改默认收货地址 &gt;
            </Link>
            <Link to="/cart" className="block text-slate-600 hover:text-rose-600">
              • 查看我的购物车商品 &gt;
            </Link>
          </div>
        </aside>

        {/* 中间：聊天消息流 */}
        <main className="flex-1 bg-white rounded-xl border border-slate-200/80 shadow-xs flex flex-col overflow-hidden">
          {/* 消息历史滚动区 */}
          <div className="flex-1 p-4 overflow-y-auto space-y-4">
            {messages.map((m) => {
              const isBot = m.sender === 'bot'
              return (
                <div
                  key={m.id}
                  className={`flex gap-3 items-start ${isBot ? '' : 'flex-row-reverse'}`}
                >
                  <Avatar
                    size={32}
                    icon={isBot ? <RobotOutlined /> : <UserOutlined />}
                    className={isBot ? 'bg-rose-600' : 'bg-slate-700'}
                  />
                  <div className={`max-w-[70%] ${isBot ? '' : 'text-right'}`}>
                    <div
                      className={`inline-block p-3 rounded-2xl text-xs leading-relaxed ${
                        isBot
                          ? 'bg-slate-100 text-slate-800 rounded-tl-none shadow-2xs'
                          : 'bg-rose-600 text-white rounded-tr-none shadow-sm'
                      }`}
                    >
                      {m.text}

                      {m.orderCard && (
                        <div className="mt-2 p-2 bg-white text-slate-800 rounded border border-rose-200 flex gap-2 items-center text-left">
                          <img
                            src={m.orderCard.image}
                            alt=""
                            className="w-10 h-10 object-cover rounded"
                          />
                          <div className="min-w-0 flex-1">
                            <p className="font-bold text-[11px] truncate">
                              {m.orderCard.title}
                            </p>
                            <span className="text-rose-600 font-bold text-xs">
                              ¥{m.orderCard.price.toFixed(2)}
                            </span>
                          </div>
                        </div>
                      )}
                    </div>
                    <div className="text-[10px] text-slate-400 mt-1">{m.time}</div>
                  </div>
                </div>
              )
            })}
            <div ref={msgEndRef} />
          </div>

          {/* 快捷问题选项胶囊 */}
          <div className="px-4 py-2 bg-slate-50/80 border-t border-slate-100 flex flex-wrap gap-2">
            {quickQuestions.map((q) => (
              <button
                key={q}
                type="button"
                onClick={() => handleSend(q)}
                className="px-2.5 py-1 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-xs text-slate-700 hover:text-rose-600 rounded-full transition cursor-pointer"
              >
                {q}
              </button>
            ))}
          </div>

          {/* 输入框与工具条 */}
          <div className="p-3 border-t border-slate-200 bg-white">
            <div className="flex gap-2">
              <Input.TextArea
                value={inputVal}
                onChange={(e) => setInputVal(e.target.value)}
                onPressEnter={(e) => {
                  if (!e.shiftKey) {
                    e.preventDefault()
                    handleSend()
                  }
                }}
                placeholder="输入您的问题，按 Enter 发送..."
                autoSize={{ minRows: 2, maxRows: 3 }}
                className="text-xs"
              />
              <Button
                type="primary"
                danger
                icon={<SendOutlined />}
                onClick={() => handleSend()}
                className="h-auto px-5 font-semibold"
              >
                发送
              </Button>
            </div>
          </div>
        </main>

        {/* 右侧：常见 FAQ */}
        <aside className="w-64 bg-white rounded-xl border border-slate-200/80 shadow-xs p-4 shrink-0 hidden lg:block">
          <h4 className="text-xs font-bold text-slate-700 pb-2 border-b border-slate-100 flex items-center gap-1">
            <QuestionCircleOutlined className="text-rose-600" /> 常见问题与帮助
          </h4>

          <ul className="mt-3 space-y-2.5 text-xs text-slate-600">
            <li
              onClick={() => handleSend('下单后多久发货？')}
              className="hover:text-rose-600 cursor-pointer p-1.5 hover:bg-slate-50 rounded"
            >
              • 下单后多长时间能发货？
            </li>
            <li
              onClick={() => handleSend('如何查询电子发票？')}
              className="hover:text-rose-600 cursor-pointer p-1.5 hover:bg-slate-50 rounded"
            >
              • 如何开具和查询电子发票？
            </li>
            <li
              onClick={() => handleSend('商品支持七天无理由退货吗？')}
              className="hover:text-rose-600 cursor-pointer p-1.5 hover:bg-slate-50 rounded"
            >
              • 七天无理由退货运费谁承担？
            </li>
            <li
              onClick={() => handleSend('PLUS 会员专属退换货权益是什么？')}
              className="hover:text-rose-600 cursor-pointer p-1.5 hover:bg-slate-50 rounded"
            >
              • PLUS 会员双向免费上门取退换
            </li>
          </ul>
        </aside>
      </div>
    </div>
  )
}
