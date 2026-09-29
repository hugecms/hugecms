import React from 'react'
import { Tag } from 'antd'
import { CheckCircleFilled } from '@ant-design/icons'

export interface PayChannel {
  id: string
  name: string
  desc: string
  tag?: string
  iconBg: string
}

export const PAY_CHANNELS: PayChannel[] = [
  {
    id: 'baitiao',
    name: '京东白条',
    desc: '先用后付，享 3 期免息特惠',
    tag: '立减 ¥30',
    iconBg: '#e1251b',
  },
  {
    id: 'wechat',
    name: '微信支付',
    desc: '亿万用户的便捷支付选择',
    iconBg: '#07c160',
  },
  {
    id: 'alipay',
    name: '支付宝支付',
    desc: '数亿用户的安全支付保障',
    iconBg: '#1677ff',
  },
  {
    id: 'bank',
    name: '银联在线 / 信用卡',
    desc: '支持各大国有银行借记卡及信用卡',
    iconBg: '#d48806',
  },
]

export interface PayChannelListProps {
  value?: string
  onChange?: (channelId: string) => void
}

export const PayChannelList: React.FC<PayChannelListProps> = ({
  value = 'baitiao',
  onChange,
}) => {
  return (
    <div className="space-y-3">
      {PAY_CHANNELS.map((ch) => {
        const isSelected = value === ch.id
        return (
          <div
            key={ch.id}
            onClick={() => onChange?.(ch.id)}
            className={`flex items-center justify-between p-4 rounded-lg border cursor-pointer transition ${
              isSelected
                ? 'border-red-600 bg-red-50/20 ring-1 ring-red-500'
                : 'border-slate-200 hover:border-slate-300 bg-white'
            }`}
          >
            <div className="flex items-center gap-3">
              <div
                className="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-xs"
                style={{ backgroundColor: ch.iconBg }}
              >
                {ch.name.slice(0, 2)}
              </div>
              <div>
                <div className="flex items-center gap-2">
                  <span className="font-bold text-slate-800 text-sm">{ch.name}</span>
                  {ch.tag && <Tag color="error" className="m-0 text-[10px]">{ch.tag}</Tag>}
                </div>
                <div className="text-xs text-slate-500 mt-0.5">{ch.desc}</div>
              </div>
            </div>

            {isSelected ? (
              <CheckCircleFilled className="text-red-600 text-lg" />
            ) : (
              <div className="w-4 h-4 rounded-full border border-slate-300" />
            )}
          </div>
        )
      })}
    </div>
  )
}

export default PayChannelList
