import React, { useState } from 'react'
import { Card, Table, Select, Input, Button, message } from 'antd'
import type { ColumnsType } from 'antd/es/table'
import { PrinterOutlined, CheckCircleOutlined } from '@ant-design/icons'
import { useSellerStore } from '../../../stores/sellerStore'
import type { DispatchOrderItem } from '../../../types/seller'

export const DispatchTable: React.FC = () => {
  const { orders, dispatchOrder } = useSellerStore()
  const [selectedExpress, setSelectedExpress] = useState<Record<string, string>>({})
  const [trackingMap, setTrackingMap] = useState<Record<string, string>>({})
  const [isPrinting, setIsPrinting] = useState(false)

  const handleDispatch = (record: DispatchOrderItem) => {
    const express = selectedExpress[record.sn] || record.expressCompany || '京东快递 (211速达)'
    const tracking = trackingMap[record.sn] ?? record.trackingNo

    if (!tracking.trim()) {
      message.warning('请先录入有效的快递运单号')
      return
    }

    dispatchOrder(record.sn, express, tracking.trim())
    message.success(`订单 ${record.sn} 发货成功！已指派 [${express} - ${tracking}]`)
  }

  const handleBatchPrint = () => {
    setIsPrinting(true)
    setTimeout(() => {
      setIsPrinting(false)
      message.success('🖨️ 京东智臻电子面单组件已调用，已批量打印当前待发货面单！')
    }, 800)
  }

  const columns: ColumnsType<DispatchOrderItem> = [
    {
      title: '订单编号',
      dataIndex: 'sn',
      key: 'sn',
      width: 170,
      render: (sn: string, record) => (
        <div>
          <span className="font-semibold text-[rgba(0,0,0,0.88)]">{sn}</span>
          <span className="block text-[11px] text-[#8c8c8c]">{record.orderTime}</span>
        </div>
      ),
    },
    {
      title: '购买商品明细',
      dataIndex: 'goodsTitle',
      key: 'goodsTitle',
      render: (_, record) => (
        <div className="flex items-center gap-3">
          <img
            src={record.goodsImage}
            alt={record.goodsTitle}
            className="h-11 w-11 rounded border border-[#f0f0f0] object-cover"
          />
          <div className="text-xs">
            <p className="mb-0.5 line-clamp-1 font-medium text-[rgba(0,0,0,0.88)]">
              {record.goodsTitle}
            </p>
            <p className="text-[11px] text-[#8c8c8c]">{record.goodsSku}</p>
          </div>
        </div>
      ),
    },
    {
      title: '实付金额',
      dataIndex: 'payAmount',
      key: 'payAmount',
      width: 110,
      render: (amount: number) => (
        <span className="font-semibold text-[rgba(0,0,0,0.88)]">¥{amount.toFixed(2)}</span>
      ),
    },
    {
      title: '买家收件信息',
      key: 'receiver',
      width: 220,
      render: (_, record) => (
        <div className="text-xs">
          <p className="font-medium text-[rgba(0,0,0,0.88)]">
            {record.receiverName} ({record.receiverPhone})
          </p>
          <p className="line-clamp-1 text-[11px] text-[#8c8c8c]">{record.receiverAddress}</p>
        </div>
      ),
    },
    {
      title: '发货物流',
      key: 'express',
      width: 160,
      render: (_, record) => (
        <Select
          size="small"
          className="w-full text-xs"
          disabled={record.status === 'shipped'}
          value={selectedExpress[record.sn] || record.expressCompany}
          onChange={(val) => setSelectedExpress({ ...selectedExpress, [record.sn]: val })}
          options={[
            { label: '京东快递 (211速达)', value: '京东快递 (211速达)' },
            { label: '顺丰速运 (特快)', value: '顺丰速运 (特快)' },
            { label: '中通快递', value: '中通快递' },
            { label: '圆通速递', value: '圆通速递' },
          ]}
        />
      ),
    },
    {
      title: '快递运单号',
      key: 'tracking',
      width: 160,
      render: (_, record) => (
        <Input
          size="small"
          disabled={record.status === 'shipped'}
          value={trackingMap[record.sn] ?? record.trackingNo}
          onChange={(e) => setTrackingMap({ ...trackingMap, [record.sn]: e.target.value })}
          placeholder="录入运单号"
          className="text-xs"
        />
      ),
    },
    {
      title: '操作',
      key: 'action',
      width: 100,
      render: (_, record) =>
        record.status === 'shipped' ? (
          <span className="inline-flex items-center gap-1 text-xs text-[#52c41a]">
            <CheckCircleOutlined /> 已出库
          </span>
        ) : (
          <Button
            type="primary"
            size="small"
            onClick={() => handleDispatch(record)}
            className="text-xs"
          >
            确认发货
          </Button>
        ),
    },
  ]

  return (
    <Card
      id="dispatchSection"
      title={
        <div className="flex items-center gap-2">
          <span className="font-semibold text-[rgba(0,0,0,0.88)]">待发货订单履约中心</span>
          <span className="text-xs font-normal text-[#8c8c8c]">
            支持极速录入运单号履约或调用电子面单极速出库
          </span>
        </div>
      }
      extra={
        <Button
          icon={<PrinterOutlined />}
          onClick={handleBatchPrint}
          loading={isPrinting}
          size="middle"
        >
          批量打印电子面单
        </Button>
      }
      className="border-[#f0f0f0] shadow-sm"
    >
      <Table
        rowKey="sn"
        columns={columns}
        dataSource={orders}
        pagination={false}
        size="middle"
        className="text-xs"
      />
    </Card>
  )
}
