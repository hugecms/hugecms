import React, { useState, useEffect } from 'react'
import { Steps, Input, Tag } from 'antd'
import { LockOutlined, SearchOutlined } from '@ant-design/icons'

interface TradeHeaderProps {
  type: 'cart' | 'checkout' | 'pay'
  currentStep?: number
}

export const TradeHeader: React.FC<TradeHeaderProps> = ({ type, currentStep = 1 }) => {
  // 30分钟收银台倒计时
  const [secondsLeft, setSecondsLeft] = useState(1799)

  useEffect(() => {
    if (type !== 'pay') return
    const timer = setInterval(() => {
      setSecondsLeft((prev) => (prev > 0 ? prev - 1 : 0))
    }, 1000)
    return () => clearInterval(timer)
  }, [type])

  const formatCountdown = (seconds: number) => {
    const mins = Math.floor(seconds / 60)
    const secs = seconds % 60
    return `${mins}分${secs < 10 ? '0' : ''}${secs}秒`
  }

  return (
    <header className="border-b border-[#e5e7eb] bg-white">
      <div className="mx-auto flex h-20 max-w-[1200px] items-center justify-between px-4">
        {/* 左侧 Logo 与业务标题 */}
        <div className="flex items-center gap-4">
          <a href="/" className="flex items-center gap-1.5 no-underline">
            <span className="flex h-9 w-9 items-center justify-center rounded bg-[#e1251b] font-sans text-xl font-black text-white">
              JD
            </span>
            <span className="text-xl font-bold tracking-tight text-[#e1251b]">京东</span>
          </a>

          <div className="h-6 w-[1px] bg-[#d1d5db]"></div>

          <h2 className="text-lg font-semibold text-[rgba(0,0,0,0.88)]">
            {type === 'cart' && '购物车'}
            {type === 'checkout' && '结算页'}
            {type === 'pay' && '收银台'}
          </h2>

          {type === 'pay' && (
            <Tag color="cyan" className="flex items-center gap-1 border-none bg-blue-50 text-blue-600">
              <LockOutlined /> 银行级安全加密防护
            </Tag>
          )}
        </div>

        {/* 右侧根据业务类型展示不同内容 */}
        {type === 'cart' && (
          <div className="w-80">
            <Input.Search
              placeholder="自营商品特惠直降"
              allowClear
              enterButton="搜索"
              size="middle"
              className="ant-search-red"
            />
          </div>
        )}

        {type === 'checkout' && (
          <div className="w-[420px]">
            <Steps
              current={currentStep}
              size="small"
              items={[
                { title: '1. 我的购物车' },
                { title: '2. 填写核对订单' },
                { title: '3. 成功提交订单' },
              ]}
            />
          </div>
        )}

        {type === 'pay' && (
          <div className="flex items-center gap-1 text-xs text-[#595959]">
            <span>请在 </span>
            <strong className="font-semibold text-[#e1251b]">
              {formatCountdown(secondsLeft)}
            </strong>
            <span> 内完成支付，超时订单将自动取消</span>
          </div>
        )}
      </div>
    </header>
  )
}
