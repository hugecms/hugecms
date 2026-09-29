import React, { useState } from 'react'
import { createFileRoute, useNavigate, Link } from '@tanstack/react-router'
import { Modal, Form, Input, Radio, message } from 'antd'
import { PlusOutlined } from '@ant-design/icons'
import { useCartStore } from '../../stores/cartStore'
import type { ShippingAddress } from '../../types/trade'
import styles from './checkout.module.css'

export const Route = createFileRoute('/_trade/checkout')({
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

  const [payType, setPayType] = useState('online')
  const [isAddrModalOpen, setIsAddrModalOpen] = useState(false)
  const [addrForm] = Form.useForm<Omit<ShippingAddress, 'id'>>()
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
      message.success('订单已成功提交！正在进入收银台...')
      navigate({ to: '/pay' })
    }, 600)
  }

  return (
    <main className={`w ${styles.checkoutMain}`}>
        <h2 className={styles.checkoutBoxTitle}>填写并核对订单信息</h2>

        <div className={styles.checkoutFormContainer}>
          {/* 1. 收货人信息 */}
          <section className={styles.checkoutSection}>
            <div className={styles.secHead}>
              <h3 className={styles.secTitle}>收货人信息</h3>
              <button
                type="button"
                className={styles.btnNewAddress}
                onClick={() => setIsAddrModalOpen(true)}
              >
                + 新增收货地址
              </button>
            </div>

            <div className={styles.addrList}>
              {addresses.map((addr) => (
                <div
                  key={addr.id}
                  className={`${styles.addrCard} ${addr.id === (currentAddress?.id || '') ? styles.active : ''}`}
                  onClick={() => selectAddress(addr.id)}
                >
                  <div className={styles.addrHeader}>
                    <span className={styles.uname}>{addr.name}</span>
                    {addr.tag && <span className={styles.addrTag}>{addr.tag}</span>}
                  </div>
                  <p className={styles.addrDetail}>
                    {addr.province} {addr.city} {addr.district} {addr.detail}
                  </p>
                  <p className={styles.phone}>{addr.phone}</p>
                </div>
              ))}
            </div>
          </section>

          {/* 2. 支付方式 */}
          <section className={styles.checkoutSection}>
            <div className={styles.secHead}>
              <h3 className={styles.secTitle}>支付方式</h3>
            </div>
            <div className={styles.payOptions}>
              <button
                type="button"
                className={`${styles.payBtn} ${payType === 'online' ? styles.active : ''}`}
                onClick={() => setPayType('online')}
              >
                在线支付（微信 / 支付宝 / 京东白条）
              </button>
              <button
                type="button"
                className={`${styles.payBtn} ${payType === 'cod' ? styles.active : ''}`}
                onClick={() => setPayType('cod')}
              >
                货到付款
              </button>
              <button
                type="button"
                className={`${styles.payBtn} ${payType === 'installment' ? styles.active : ''}`}
                onClick={() => setPayType('installment')}
              >
                白条免息分期
              </button>
            </div>
          </section>

          {/* 3. 送货清单 */}
          <section className={styles.checkoutSection}>
            <div className={styles.secHead}>
              <h3 className={styles.secTitle}>送货清单</h3>
              <Link to="/cart" style={{ color: '#005ea7', fontSize: '12px' }}>
                返回购物车修改 &gt;
              </Link>
            </div>

            <div className={styles.deliveryList}>
              {selectedItems.map((item) => (
                <div key={item.id} className={styles.goodsItemRow}>
                  <img src={item.image} alt={item.title} className={styles.goodsThumb} />
                  <Link
                    to="/item/$id"
                    params={{ id: String(item.goodsId) }}
                    className={styles.goodsName}
                  >
                    {item.title}
                  </Link>
                  <div className={styles.goodsSku}>{item.sku}</div>
                  <div className={styles.goodsPrice}>¥{item.price.toFixed(2)}</div>
                  <div className={styles.goodsNum}>× {item.quantity}</div>
                </div>
              ))}
            </div>
          </section>
        </div>

        {/* 4. 汇总统计与提交订单 */}
        <div className={styles.orderSummaryBox}>
          <div className={styles.sumRow}>
            <span>{selectedCount} 件商品，总商品金额：</span>
            <span className={styles.sumVal}>¥{totalPrice.toFixed(2)}</span>
          </div>
          <div className={styles.sumRow}>
            <span>运费：</span>
            <span className={styles.sumVal}>¥0.00</span>
          </div>
          <div className={styles.sumRow}>
            <span>返现与满减：</span>
            <span className={styles.sumValHighlight}>-¥{discountAmount.toFixed(2)}</span>
          </div>

          <div className={styles.submitBar}>
            <div className={styles.finalAmountBox}>
              <span>应付总额：</span>
              <strong className={styles.finalAmount}>¥{finalPayAmount.toFixed(2)}</strong>
              <div style={{ fontSize: '12px', color: '#999', marginTop: '4px' }}>
                寄送至：{currentAddress?.province} {currentAddress?.city} {currentAddress?.detail} 收货人：{currentAddress?.name} {currentAddress?.phone}
              </div>
            </div>
            <button
              type="button"
              className={styles.btnSubmitOrder}
              disabled={submitting || selectedItems.length === 0}
              onClick={handleSubmitOrder}
            >
              {submitting ? '正在提交...' : '提交订单'}
            </button>
          </div>
        </div>

        {/* 新增收货地址 Modal */}
        <Modal
          title="新增收货地址"
          open={isAddrModalOpen}
          onCancel={() => setIsAddrModalOpen(false)}
          onOk={() => addrForm.submit()}
          okText="保存并使用"
          cancelText="取消"
        >
          <Form form={addrForm} layout="vertical" onFinish={handleAddAddress}>
            <Form.Item name="name" label="收件人姓名" rules={[{ required: true, message: '请输入收件人姓名' }]}>
              <Input placeholder="请填写收货人真实姓名" />
            </Form.Item>
            <Form.Item name="phone" label="联系电话" rules={[{ required: true, message: '请输入手机号码' }]}>
              <Input placeholder="请填写11位手机号码" />
            </Form.Item>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '8px' }}>
              <Form.Item name="province" label="省份" rules={[{ required: true, message: '必填' }]}>
                <Input placeholder="北京市" />
              </Form.Item>
              <Form.Item name="city" label="城市" rules={[{ required: true, message: '必填' }]}>
                <Input placeholder="朝阳区" />
              </Form.Item>
              <Form.Item name="district" label="街道" rules={[{ required: true, message: '必填' }]}>
                <Input placeholder="建国门外街道" />
              </Form.Item>
            </div>
            <Form.Item name="detail" label="详细地址" rules={[{ required: true, message: '请输入详细地址' }]}>
              <Input placeholder="如：建国路88号 SOHO现代城 A座 1802室" />
            </Form.Item>
            <Form.Item name="tag" label="地址标签">
              <Radio.Group defaultValue="家">
                <Radio.Button value="家">家</Radio.Button>
                <Radio.Button value="公司">公司</Radio.Button>
                <Radio.Button value="学校">学校</Radio.Button>
              </Radio.Group>
            </Form.Item>
          </Form>
        </Modal>
      </main>
  )
}

export default CheckoutPage
