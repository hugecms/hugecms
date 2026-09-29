import React from 'react'
import { Modal, Form, Input, Checkbox } from 'antd'
import type { ShippingAddress } from '../../../types/trade'

export interface AddressModalProps {
  open: boolean
  onClose: () => void
  onSubmit: (values: Omit<ShippingAddress, 'id'>) => void
  initialValues?: Partial<ShippingAddress>
}

export const AddressModal: React.FC<AddressModalProps> = ({
  open,
  onClose,
  onSubmit,
  initialValues,
}) => {
  const [form] = Form.useForm()

  const handleOk = async () => {
    try {
      const values = await form.validateFields()
      onSubmit(values)
      form.resetFields()
      onClose()
    } catch {
      // 验证未通过
    }
  }

  return (
    <Modal
      title={initialValues?.id ? '编辑收货地址' : '新增收货地址'}
      open={open}
      onOk={handleOk}
      onCancel={onClose}
      okText="保存地址"
      cancelText="取消"
      destroyOnClose
    >
      <Form
        form={form}
        layout="vertical"
        initialValues={
          initialValues || {
            isDefault: false,
            tag: '家',
          }
        }
      >
        <Form.Item
          name="name"
          label="收件人姓名"
          rules={[{ required: true, message: '请输入收件人姓名' }]}
        >
          <Input placeholder="请填写收件人姓名" />
        </Form.Item>

        <Form.Item
          name="phone"
          label="手机号码"
          rules={[
            { required: true, message: '请输入手机号码' },
            { pattern: /^1[3-9]\d{9}$/, message: '请输入有效的11位手机号码' },
          ]}
        >
          <Input placeholder="请填写11位手机号码" />
        </Form.Item>

        <div className="grid grid-cols-3 gap-2">
          <Form.Item
            name="province"
            label="省份"
            rules={[{ required: true, message: '请输入省份' }]}
          >
            <Input placeholder="北京市" />
          </Form.Item>
          <Form.Item
            name="city"
            label="城市"
            rules={[{ required: true, message: '请输入城市' }]}
          >
            <Input placeholder="市辖区" />
          </Form.Item>
          <Form.Item
            name="district"
            label="区县"
            rules={[{ required: true, message: '请输入区县' }]}
          >
            <Input placeholder="大兴区" />
          </Form.Item>
        </div>

        <Form.Item
          name="detail"
          label="详细地址"
          rules={[{ required: true, message: '请输入详细地址' }]}
        >
          <Input.TextArea rows={2} placeholder="如：亦庄经济技术开发区科创十一街18号院京东总部" />
        </Form.Item>

        <Form.Item name="tag" label="地址标签">
          <Input placeholder="如：家、公司、学校" />
        </Form.Item>

        <Form.Item name="isDefault" valuePropName="checked">
          <Checkbox>设为默认收货地址</Checkbox>
        </Form.Item>
      </Form>
    </Modal>
  )
}

export default AddressModal
