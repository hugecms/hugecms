import React from 'react'
import { Modal, Form, Input, InputNumber, Select, message } from 'antd'
import { useSellerStore } from '../../../stores/sellerStore'
import type { NewGoodsFormData } from '../../../types/seller'

export const AddGoodsModal: React.FC = () => {
  const { isAddModalOpen, closeAddModal, addInventoryItem } = useSellerStore()
  const [form] = Form.useForm<NewGoodsFormData>()

  const handleFinish = (values: NewGoodsFormData) => {
    addInventoryItem(values)
    message.success('🎉 新商品已通过安全质检并成功上架至前台店铺！')
    form.resetFields()
    closeAddModal()
  }

  return (
    <Modal
      title={<span className="text-base font-semibold text-[rgba(0,0,0,0.88)]">发布新商品 (SPU/SKU 配置)</span>}
      open={isAddModalOpen}
      onCancel={closeAddModal}
      onOk={() => form.submit()}
      okText="审核并上架到前台店铺"
      cancelText="取消"
      width={620}
      destroyOnClose
    >
      <Form
        form={form}
        layout="vertical"
        onFinish={handleFinish}
        initialValues={{
          category: '微单相机',
          stock: 50,
          logisticsMode: 'POP自主发货',
          imageUrl: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=400&q=80',
        }}
        className="pt-2"
      >
        <Form.Item
          name="title"
          label="商品标题"
          rules={[{ required: true, message: '请输入商品标题' }]}
        >
          <Input placeholder="如：索尼 (SONY) Alpha 7 IV 全画幅微单数码相机 单机身" />
        </Form.Item>

        <div className="grid grid-cols-2 gap-4">
          <Form.Item
            name="category"
            label="所属商品类目"
            rules={[{ required: true, message: '请选择商品类目' }]}
          >
            <Select
              options={[
                { label: '3C数码 > 摄影摄像 > 微单相机', value: '微单相机' },
                { label: '3C数码 > 手机通讯 > 5G智能机', value: '智能手机' },
                { label: '3C数码 > 智能影音 > 头戴耳机', value: '影音耳机' },
              ]}
            />
          </Form.Item>

          <Form.Item
            name="price"
            label="统一售价 (元)"
            rules={[{ required: true, message: '请输入商品售价' }]}
          >
            <InputNumber
              min={0.01}
              precision={2}
              placeholder="15999.00"
              className="w-full"
            />
          </Form.Item>
        </div>

        <div className="grid grid-cols-2 gap-4">
          <Form.Item
            name="stock"
            label="首批入库总件数"
            rules={[{ required: true, message: '请输入库存件数' }]}
          >
            <InputNumber min={1} placeholder="50" className="w-full" />
          </Form.Item>

          <Form.Item name="logisticsMode" label="物流发货模式">
            <Select
              options={[
                { label: '商家自主发货 (顺丰/京东快递)', value: 'POP自主发货' },
                { label: '入京东八大仓一体仓 (带自营标)', value: '京东仓配自营入仓' },
              ]}
            />
          </Form.Item>
        </div>

        <Form.Item name="imageUrl" label="商品主图 URL">
          <Input placeholder="请输入商品主图网络图片地址" />
        </Form.Item>
      </Form>
    </Modal>
  )
}
