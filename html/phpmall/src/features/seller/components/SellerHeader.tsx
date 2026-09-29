import React from 'react'
import { Badge, message } from 'antd'
import { BellOutlined, CustomerServiceOutlined, ShopOutlined, HomeOutlined, UserOutlined } from '@ant-design/icons'

export const SellerHeader: React.FC = () => {
  const handleNoticeClick = () => {
    message.info('收到 3 条京东平台官方营商公告与类目规则变更提醒')
  }

  const handleImClick = () => {
    message.info('正在唤起京麦客服协同工作台...')
  }

  return (
    <header className="sticky top-0 z-[1000] flex h-14 w-full items-center justify-between bg-[#1e293b] px-6 text-white shadow-sm">
      <div className="flex items-center gap-5">
        <a href="/seller" className="flex items-center gap-2 no-underline">
          <span className="rounded bg-[#1677ff] px-2 py-0.5 text-base font-black tracking-wider text-white">
            京麦
          </span>
          <span className="text-[15px] font-semibold text-[#f1f5f9]">商家工作台</span>
        </a>
        <div className="flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
          <span className="text-xs font-medium text-white">索尼数码专营店</span>
          <span className="rounded-full bg-[#e1251b] px-1.5 py-0.5 text-[10px] font-medium text-white">
            POP专营店
          </span>
          <span className="text-[11px] text-[#ffd666]">综合评分 4.9</span>
        </div>
      </div>

      <div className="flex items-center gap-4 text-xs text-[#94a3b8]">
        <a
          href="/"
          className="flex items-center gap-1 text-[#94a3b8] transition-colors hover:text-white"
        >
          <ShopOutlined className="text-sm" /> 前台店铺首页
        </a>
        <a
          href="/"
          className="flex items-center gap-1 text-[#94a3b8] transition-colors hover:text-white"
        >
          <HomeOutlined className="text-sm" /> 京东商城
        </a>
        <span className="text-[#475569]">|</span>
        <button
          type="button"
          onClick={handleNoticeClick}
          className="flex cursor-pointer items-center gap-1.5 bg-transparent text-[#94a3b8] transition-colors hover:text-white"
        >
          <Badge count={3} size="small" offset={[2, -2]}>
            <BellOutlined className="text-sm text-[#94a3b8]" />
          </Badge>
          <span>消息</span>
        </button>
        <button
          type="button"
          onClick={handleImClick}
          className="flex cursor-pointer items-center gap-1 bg-transparent text-[#94a3b8] transition-colors hover:text-white"
        >
          <CustomerServiceOutlined className="text-sm" /> 商家客服IM
        </button>
        <span className="text-[#475569]">|</span>
        <div className="flex items-center gap-2 text-[#e2e8f0]">
          <div className="flex h-6 w-6 items-center justify-center rounded-full bg-white/15 text-xs">
            <UserOutlined />
          </div>
          <span>李店长 (主账号)</span>
          <button
            type="button"
            onClick={() => message.info('已安全退出工作台')}
            className="cursor-pointer bg-transparent text-xs text-[#ff7875] hover:text-[#ff4d4f] hover:underline"
          >
            退出
          </button>
        </div>
      </div>
    </header>
  )
}
