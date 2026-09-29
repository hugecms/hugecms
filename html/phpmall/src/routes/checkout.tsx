import React, { useState } from 'react'
import { createFileRoute, useNavigate } from '@tanstack/react-router'
import { Card, Button, Radio, Tag, Modal, Form, Input, message } from 'antd'
import { PlusOutlined, CheckOutlined, EnvironmentOutlined } from '@ant-design/icons'
import { useCartStore } from '../stores/cartStore'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { TradeHeader } from '../features/trade/components/TradeHeader'
import { MallFooter } from '../features/mall/components/MallFooter'
import type { ShippingAddress } from '../types/trade'

export const Route = createFileRoute('/checkout')({
  component: CheckoutPage,
})

function CheckoutPage() {
  const navigate = useNavigate()
  const {
    addresses,
    selectedAddressId,
    selectAddress,
    addAddress,
    getSelectedItems,
    getSelectedCount,
    getTotalPrice,
    getDiscountAmount,
    getFinalPayAmount,
  } = useCartStore()

  const selectedItems = getSelectedItems()
  const selectedCount = getSelectedCount()
  const totalPrice = getTotalPrice()
  const discountAmount = getDiscountAmount()
  const finalPayAmount = getFinalPayAmount()

  // 支付方式
  const [payType, setPayType] = useState('online')
  // 新增地址 Modal
  const [isAddrModalOpen, setIsAddrModalOpen] = useState(false)
  const [addrForm] = Form.useForm<Omit<ShippingAddress, 'id'>>()
  // 提交订单加载中
  const [submitting, setSubmitting] = useState(false)

  const currentAddress =
    addresses.find((a) => a.id === selectedAddressId) || addresses[0]

  const handleAddAddress = (values: Omit<ShippingAddress, 'id'>) => {
    addAddress(values)
    message.success('新收货地址已添加')
    addrForm.resetFields()
    setIsAddrModalOpen(false)
  }

  const handleSubmitOrder = () => {
    if (!currentAddress) {
      message.warning('请选择收货地址')
      return
    }
    if (selectedItems.length === 0) {
      message.warning('订单内暂无商品，请返回购物车挑选')
      return
    }

    setSubmitting(true)
    setTimeout(() => {
      setSubmitting(false)
      message.success('订单提交成功，正在前往收银台...')
      navigate({ to: '/pay' })
    }, 700)
  }

  return (
    <div className="min-h-screen bg-[#f4f5f7]">
      <MallShortcutNav />
      <TradeHeader type="checkout" currentStep={1} />

      <main className="mx-auto max-w-[1200px] px-4 py-5">
        <div className="mb-4 text-base font-bold text-[rgba(0,0,0,0.88)]">
          填写并核对订单信息
        </div>

        <div className="space-y-4 rounded-lg bg-white p-6 shadow-sm">
          {/* 1. 收货人信息 */}
          <section className="border-b border-[#f0f0f0] pb-6">
            <div className="mb-3 flex items-center justify-between">
              <h3 className="text-sm font-semibold text-[rgba(0,0,0,0.88)]">
                收货人信息
              </h3>
              <Button
                type="link"
                icon={<PlusOutlined />}
                size="small"
                onClick={() => setIsAddrModalOpen(true)}
              >
                新增收货地址
              </Button>
            </div>

            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
              {addresses.map((addr) => {
                const isSelected = addr.id === selectedAddressId
                return (
                  <div
                    key={addr.id}
                    onClick={() => selectAddress(addr.id)}
                    className={`relative cursor-pointer rounded-lg border p-3.5 transition-all ${
                      isSelected
                        ? 'border-[#e1251b] bg-[#fffbfb] shadow-sm'
                        : 'border-[#e5e7eb] hover:border-[#999]'
                    }`}
                  >
                    <div className="flex items-center justify-between text-xs">
                      <span className="font-semibold text-[rgba(0,0,0,0.88)]">
                        {addr.name} ({addr.province})
                      </span>
                      {addr.tag && (
                        <Tag color={isSelected ? 'red' : 'default'} className="m-0 text-[10px]">
                          {addr.tag}
                        </Tag>
                      )}
                    </div>
                    <p className="mt-1.5 text-xs text-[#595959]">
                      {addr.province} {addr.city} {addr.district} {addr.detailAddress}
                    </p>
                    <p className="mt-1 text-xs text-[#8c8c8c]">{addr.phone}</p>
                    {isSelected && (
                      <span className="absolute bottom-0 right-0 rounded-tl bg-[#e1251b] p-1 text-[10px] text-white">
                        <CheckOutlined />
                      </span>
                    )}
                  </div>
                )
              })}
            </div>
          </section>

          {/* 2. 支付方式 */}
          <section className="border-b border-[#f0f0f0] pb-6">
            <h3 className="mb-3 text-sm font-semibold text-[rgba(0,0,0,0.88)]">
              支付方式
            </h3>
            <div className="flex gap-3">
              <Button
                type={payType === 'online' ? 'primary' : 'default'}
                onClick={() => setPayType('online')}
                className={payType === 'online' ? 'bg-[#e1251b] border-[#e1251b]' : ''}
              >
                在线支付 (支持微信/支付宝/白条)
              </Button>
              <Button
                type={payType === 'cod' ? 'primary' : 'default'}
                onClick={() => setPayType('cod')}
                className={payType === 'cod' ? 'bg-[#e1251b] border-[#e1251b]' : ''}
              >
                货到付款
              </Button>
            </div>
          </section>

          {/* 3. 送货清单与商品列表 */}
          <section className="border-b border-[#f0f0f0] pb-6">
            <h3 className="mb-3 text-sm font-semibold text-[rgba(0,0,0,0.88)]">
              送货清单
            </h3>
            <div className="rounded border border-[#f0f0f0] bg-[#fafafa] p-4">
              <div className="mb-3 flex items-center justify-between text-xs text-[#666]">
                <span className="font-semibold text-[rgba(0,0,0,0.88)]">
                  配送服务：京东快递 (211 限时达)
                </span>
                <span className="text-[#52c41a]">已享满 99 元免运费</span>
              </div>

              <div className="space-y-3">
                {selectedItems.map((item) => (
                  <div
                    key={item.id}
                    className="flex items-center justify-between border-t border-[#f0f0f0] pt-3 text-xs"
                  >
                    <div className="flex items-center gap-3">
                      <img
                        src={item.imageUrl}
                        alt={item.title}
                        className="h-12 w-12 rounded border border-[#f0f0f0] object-cover"
                      />
                      <div>
                        <p className="line-clamp-1 font-medium text-[rgba(0,0,0,0.88)]">
                          {item.title}
                        </p>
                        <p className="text-[11px] text-[#8c8c8c]">{item.sku}</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <span className="font-semibold text-[rgba(0,0,0,0.88)]">
                        ¥{item.price.toFixed(2)}
                      </span>
                      <span className="ml-3 text-[#8c8c8c]">× {item.quantity}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </section>

          {/* 4. 发票与优惠抵扣 */}
          <section className="flex items-center justify-between border-b border-[#f0f0f0] pb-6 text-xs text-[#666]">
            <div>
              <span>发票信息：</span>
              <strong className="text-[rgba(0,0,0,0.88)]">电子普通发票 (个人 - 明细)</strong>
            </div>
            <div>
              <span>优惠券：</span>
              <Tag color="red">满 300 减 50 元券 (已抵扣)</Tag>
            </div>
          </section>

          {/* 5. 金额汇总与提交结算卡片 */}
          <div className="flex justify-end pt-4">
            <div className="w-80 space-y-2 text-right text-xs text-[#666]">
              <div className="flex justify-between">
                <span>{selectedCount} 件商品，总商品金额：</span>
                <span className="text-[rgba(0,0,0,0.88)]">¥{totalPrice.toFixed(2)}</span>
              </div>
              <div className="flex justify-between">
                <span>返现/满减优惠：</span>
                <span className="text-[#e1251b]">-¥{discountAmount.toFixed(2)}</span>
              </div>
              <div className="flex justify-between">
                <span>运费：</span>
                <span className="text-[rgba(0,0,0,0.88)]">¥0.00</span>
              </div>

              <div className="mt-3 border-t border-[#f0f0f0] pt-3">
                <div className="flex items-baseline justify-end gap-2">
                  <span className="text-sm font-semibold text-[rgba(0,0,0,0.88)]">
                    应付总额：
                  </span>
                  <strong className="text-2xl font-bold text-[#e1251b]">
                    ¥{finalPayAmount.toFixed(2)}
                  </strong>
                </div>
                {currentAddress && (
                  <p className="mt-1.5 text-[11px] text-[#8c8c8c]">
                    寄送至：{currentAddress.province} {currentAddress.city} {currentAddress.detailAddress}
                    <br />
                    收货人：{currentAddress.name} {currentAddress.phone}
                  </p>
                )}
              </div>

              <Button
                type="primary"
                size="large"
                loading={submitting}
                onClick={handleSubmitOrder}
                className="mt-3 h-11 w-full rounded bg-[#e1251b] text-base font-bold hover:bg-[#cf1322]"
              >
                提交订单
              </Button>
            </div>
          </div>
        </div>
      </main>

      {/* 新增地址弹窗 */}
      <Modal
        title="新增收货地址"
        open={isAddrModalOpen}
        onCancel={() => setIsAddrModalOpen(false)}
        onOk={() => addrForm.submit()}
        okText="保存并使用"
        cancelText="取消"
        width={520}
      >
        <Form form={addrForm} layout="vertical" onFinish={handleAddAddress} className="pt-2">
          <div className="grid grid-cols-2 gap-3">
            <Form.Item
              name="name"
              label="收件人姓名"
              rules={[{ required: true, message: '请输入收件人姓名' }]}
            >
              <Input placeholder="张三" />
            </Form.Item>
            <Form.Item
              name="phone"
              label="手机号码"
              rules={[{ required: true, message: '请输入手机号码' }]}
            >
              <Input placeholder="13800000000" />
            </Form.Item>
          </div>
          <div className="grid grid-cols-3 gap-3">
            <Form.Item
              name="province"
              label="省份"
              initialValue="北京市"
              rules={[{ required: true }]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              name="city"
              label="城市"
              initialValue="朝阳区"
              rules={[{ required: true }]}
            >
              <Input />
            </Form.Item>
            <Form.Item
              name="district"
              label="街道"
              initialValue="建国路"
              rules={[{ required: true }]}
            >
              <Input />
            </Form.Item>
          </div>
          <Form.Item
            name="detailAddress"
            label="详细地址"
            rules={[{ required: true, message: '请输入详细门牌楼栋' }]}
          >
            <Input placeholder="如：建国路88号 SOHO现代城 A座 1802室" />
          </Form.Item>
          <Form.Item name="tag" label="地址标签">
            <Radio.Group>
              <Radio.Button value="家">家</Radio.Button>
              <Radio.Button value="公司">公司</Radio.Button>
              <Radio.Button value="学校">学校</Radio.Button>
            </Radio.Group>
          </Form.Item>
        </Form>
      </Modal>

      <MallFooter />
    </div>
  )
}
