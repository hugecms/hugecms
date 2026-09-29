import React from 'react'
import { Card, Statistic } from 'antd'
import { ArrowUpOutlined } from '@ant-design/icons'

export const MetricsCards: React.FC = () => {
  return (
    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <Card hoverable className="border-[#f0f0f0] shadow-sm">
        <Statistic
          title={<span className="text-xs text-[#8c8c8c]">今日支付金额 (GMV)</span>}
          value={38620.0}
          precision={2}
          prefix="¥"
          valueStyle={{ color: 'rgba(0, 0, 0, 0.88)', fontWeight: 600, fontSize: 26 }}
        />
        <div className="mt-2 flex items-center gap-1 text-xs text-[#3f8600]">
          <ArrowUpOutlined />
          <span>较昨日 +15.4%</span>
        </div>
      </Card>

      <Card hoverable className="border-[#f0f0f0] shadow-sm">
        <Statistic
          title={<span className="text-xs text-[#8c8c8c]">今日支付订单数</span>}
          value={14}
          suffix={<span className="text-xs font-normal text-[#8c8c8c]">单</span>}
          valueStyle={{ color: 'rgba(0, 0, 0, 0.88)', fontWeight: 600, fontSize: 26 }}
        />
        <div className="mt-2 flex items-center gap-1 text-xs text-[#3f8600]">
          <ArrowUpOutlined />
          <span>较昨日 +3单</span>
        </div>
      </Card>

      <Card hoverable className="border-[#f0f0f0] shadow-sm">
        <Statistic
          title={<span className="text-xs text-[#8c8c8c]">今日访客数 (UV)</span>}
          value={1820}
          suffix={<span className="text-xs font-normal text-[#8c8c8c]">人</span>}
          valueStyle={{ color: 'rgba(0, 0, 0, 0.88)', fontWeight: 600, fontSize: 26 }}
        />
        <div className="mt-2 flex items-center gap-1 text-xs text-[#3f8600]">
          <ArrowUpOutlined />
          <span>较昨日 +240人</span>
        </div>
      </Card>

      <Card hoverable className="border-[#f0f0f0] shadow-sm">
        <Statistic
          title={<span className="text-xs text-[#8c8c8c]">支付转化率</span>}
          value={3.85}
          suffix="%"
          valueStyle={{ color: 'rgba(0, 0, 0, 0.88)', fontWeight: 600, fontSize: 26 }}
        />
        <div className="mt-2 text-xs text-[#8c8c8c]">
          <span>高于同行平均水平</span>
        </div>
      </Card>
    </div>
  )
}
