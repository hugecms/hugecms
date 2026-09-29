import React from 'react'
import { Alert, Button, message } from 'antd'
import { AlertOutlined, WarningOutlined, GiftOutlined } from '@ant-design/icons'
import { useSellerStore } from '../../../stores/sellerStore'

export const TodoAlerts: React.FC = () => {
  const { orders } = useSellerStore()
  const pendingDeliverCount = orders.filter((o) => o.status === 'pending').length

  const scrollToDispatch = () => {
    const el = document.getElementById('dispatchSection')
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' })
    }
  }

  return (
    <div className="grid grid-cols-1 gap-3 md:grid-cols-3">
      <Alert
        type="error"
        showIcon
        icon={<AlertOutlined className="text-base" />}
        title={
          <div className="flex items-center justify-between text-xs">
            <span>
              <strong>{pendingDeliverCount} 笔</strong> 待发货订单需在 24 小时内发出
            </span>
            <Button size="small" onClick={scrollToDispatch} className="h-6 px-2 text-xs">
              立即处理
            </Button>
          </div>
        }
        className="rounded-lg border-[#ffccc7] bg-[#fff2f0]"
      />

      <Alert
        type="warning"
        showIcon
        icon={<WarningOutlined className="text-base" />}
        title={
          <div className="flex items-center justify-between text-xs">
            <span>
              <strong>1 笔</strong> 买家申请仅退款待审核
            </span>
            <Button
              size="small"
              onClick={() => message.info('正在同步买家仅退款售后凭证与举证记录...')}
              className="h-6 px-2 text-xs"
            >
              前往审核
            </Button>
          </div>
        }
        className="rounded-lg border-[#ffe58f] bg-[#fffbe6]"
      />

      <Alert
        type="info"
        showIcon
        icon={<GiftOutlined className="text-base" />}
        title={
          <div className="flex items-center justify-between text-xs">
            <span>
              年终大促数码专场招商开启，可提报 <strong>5款</strong>
            </span>
            <Button
              size="small"
              onClick={() => message.success('大促绿色申报通道已开启')}
              className="h-6 px-2 text-xs"
            >
              立即报名
            </Button>
          </div>
        }
        className="rounded-lg border-[#91caff] bg-[#e6f4ff]"
      />
    </div>
  )
}
