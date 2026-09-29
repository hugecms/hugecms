import React from 'react'
import { Card, Table, Tag, Button, Modal, InputNumber, message } from 'antd'
import type { ColumnsType } from 'antd/es/table'
import { useSellerStore } from '../../../stores/sellerStore'
import type { InventoryItem } from '../../../types/seller'

export const InventoryTable: React.FC = () => {
  const { inventory, restockItem } = useSellerStore()

  const handleRestock = (item: InventoryItem) => {
    let restockCount = 50
    Modal.confirm({
      title: `为【${item.name.slice(0, 16)}...】补货入库`,
      content: (
        <div className="py-2">
          <p className="mb-2 text-xs text-[#595959]">请输入追加库存数量：</p>
          <InputNumber
            min={1}
            defaultValue={50}
            className="w-full"
            onChange={(val) => {
              if (val) restockCount = val
            }}
          />
        </div>
      ),
      okText: '确认补货',
      cancelText: '取消',
      onOk: () => {
        restockItem(item.id, restockCount)
        message.success(`商品库存已成功追加 ${restockCount} 件！`)
      },
    })
  }

  const columns: ColumnsType<InventoryItem> = [
    {
      title: '商品名称',
      dataIndex: 'name',
      key: 'name',
      render: (name: string, record) => (
        <div className="flex items-center gap-1.5">
          <span className="font-medium text-[rgba(0,0,0,0.88)]">{name}</span>
          {record.isNew && <Tag color="blue">新品</Tag>}
        </div>
      ),
    },
    {
      title: '当前售价',
      dataIndex: 'price',
      key: 'price',
      width: 120,
      render: (price: number) => <span>¥{price.toFixed(2)}</span>,
    },
    {
      title: '今日销量',
      dataIndex: 'salesToday',
      key: 'salesToday',
      width: 100,
      render: (sales: number) => <span>{sales} 件</span>,
    },
    {
      title: '剩余库存',
      dataIndex: 'stock',
      key: 'stock',
      width: 120,
      render: (stock: number, record) => (
        <span
          className={
            record.status === 'warning' ? 'font-bold text-[#cf1322]' : 'font-bold text-[rgba(0,0,0,0.88)]'
          }
        >
          {stock} 件
        </span>
      ),
    },
    {
      title: '库存状态',
      dataIndex: 'status',
      key: 'status',
      width: 120,
      render: (status: 'normal' | 'warning') =>
        status === 'warning' ? (
          <Tag color="warning">库存紧缺</Tag>
        ) : (
          <Tag color="success">充足</Tag>
        ),
    },
    {
      title: '操作',
      key: 'action',
      width: 140,
      render: (_, record) => (
        <Button
          type="link"
          size="small"
          onClick={() => handleRestock(record)}
          className={record.status === 'warning' ? 'text-[#cf1322] hover:text-[#f5222d]' : ''}
        >
          {record.status === 'warning' ? '快速补货' : '修改库存/价格'}
        </Button>
      ),
    },
  ]

  return (
    <Card
      title={<span className="font-semibold text-[rgba(0,0,0,0.88)]">店内热销商品与库存预警</span>}
      className="border-[#f0f0f0] shadow-sm"
    >
      <Table
        rowKey="id"
        columns={columns}
        dataSource={inventory}
        pagination={false}
        size="middle"
        className="text-xs"
      />
    </Card>
  )
}
