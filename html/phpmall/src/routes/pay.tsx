import React, { useState } from 'react'
import { createFileRoute, useNavigate, Link } from '@tanstack/react-router'
import { Modal, Result, message } from 'antd'
import { useCartStore } from '../stores/cartStore'
import { TradeLayout } from '../layouts/TradeLayout'
import styles from './pay.module.css'

export const Route = createFileRoute('/pay')({
  component: PayCashierPage,
})

function PayCashierPage() {
  const navigate = useNavigate()
  const { getFinalPayAmount, clearCart } = useCartStore()
  const finalAmount = getFinalPayAmount() || 7999.0

  const [activeTab, setActiveTab] = useState<'baitiao' | 'wechat' | 'alipay'>('baitiao')
  const [selectedPlan, setSelectedPlan] = useState(1)
  const [paying, setPaying] = useState(false)
  const [paySuccess, setPaySuccess] = useState(false)

  const handlePay = () => {
    setPaying(true)
    setTimeout(() => {
      setPaying(false)
      setPaySuccess(true)
      clearCart()
      message.success('支付成功！')
    }, 1000)
  }

  return (
    <TradeLayout title="京东收银台" showSearch={false}>
      <main className={`w ${styles.payMain}`}>
        {/* 1. 订单摘要卡片 */}
        <div className={styles.payOrderSummary}>
          <div className={styles.orderPrimaryInfo}>
            <div className={styles.checkIcon}>✔</div>
            <div className={styles.orderInfoText}>
              <h3>订单提交成功，请尽快完成支付！</h3>
              <div className={styles.orderMeta}>
                <span>订单号：JD2026092988102</span>
                <span>|</span>
                <span>请在 <strong>23小时59分</strong> 内完成支付，超时订单将自动关闭</span>
              </div>
            </div>
          </div>
          <div className={styles.orderPayPrice}>
            <span className={styles.txt}>应付金额：</span>
            <span className={styles.num}>¥{finalAmount.toFixed(2)}</span>
          </div>
        </div>

        {/* 2. 支付方式选择 */}
        <div className={styles.payChannelBox}>
          <div className={styles.channelTabs}>
            <div
              className={`${styles.channelTab} ${activeTab === 'baitiao' ? styles.active : ''}`}
              onClick={() => setActiveTab('baitiao')}
            >
              💳 京东白条（立减优惠）
            </div>
            <div
              className={`${styles.channelTab} ${activeTab === 'wechat' ? styles.active : ''}`}
              onClick={() => setActiveTab('wechat')}
            >
              💬 微信支付
            </div>
            <div
              className={`${styles.channelTab} ${activeTab === 'alipay' ? styles.active : ''}`}
              onClick={() => setActiveTab('alipay')}
            >
              🔷 支付宝
            </div>
          </div>

          <div className={styles.channelBody}>
            {activeTab === 'baitiao' && (
              <div>
                <p style={{ fontSize: '13px', color: '#666' }}>
                  打白条，享分期优惠。可用白条额度：<strong style={{ color: '#e1251b' }}>¥35,000.00</strong>
                </p>
                <div className={styles.baitiaoGrid}>
                  <div
                    className={`${styles.baitiaoCard} ${selectedPlan === 1 ? styles.active : ''}`}
                    onClick={() => setSelectedPlan(1)}
                  >
                    <div className={styles.planPeriods}>不分期（次月还款）</div>
                    <div className={styles.planAmount}>¥{finalAmount.toFixed(2)}</div>
                    <div className={styles.planFee}>免手续费 · 0服务费</div>
                  </div>
                  <div
                    className={`${styles.baitiaoCard} ${selectedPlan === 3 ? styles.active : ''}`}
                    onClick={() => setSelectedPlan(3)}
                  >
                    <div className={styles.planPeriods}>3期（免息专享）</div>
                    <div className={styles.planAmount}>¥{(finalAmount / 3).toFixed(2)} / 期</div>
                    <div className={styles.planFee}>限时免息 · 手续费 ¥0</div>
                  </div>
                  <div
                    className={`${styles.baitiaoCard} ${selectedPlan === 6 ? styles.active : ''}`}
                    onClick={() => setSelectedPlan(6)}
                  >
                    <div className={styles.planPeriods}>6期分期</div>
                    <div className={styles.planAmount}>¥{((finalAmount * 1.03) / 6).toFixed(2)} / 期</div>
                    <div className={styles.planFee}>费率 0.5%/期 · 轻松无忧</div>
                  </div>
                </div>

                <div style={{ textAlign: 'right' }}>
                  <button
                    type="button"
                    className={styles.btnPaySubmit}
                    disabled={paying}
                    onClick={handlePay}
                  >
                    {paying ? '正在调起支付...' : `立即白条支付 ¥${finalAmount.toFixed(2)}`}
                  </button>
                </div>
              </div>
            )}

            {activeTab === 'wechat' && (
              <div className={styles.qrcodeArea}>
                <div className={styles.qrcodeBox}>
                  {/* 内联 SVG 二维码 */}
                  <svg viewBox="0 0 100 100" className={styles.qrcodeImg}>
                    <rect width="100" height="100" fill="#fff" />
                    <rect x="10" y="10" width="25" height="25" fill="#07c160" />
                    <rect x="65" y="10" width="25" height="25" fill="#07c160" />
                    <rect x="10" y="65" width="25" height="25" fill="#07c160" />
                    <rect x="15" y="15" width="15" height="15" fill="#fff" />
                    <rect x="70" y="15" width="15" height="15" fill="#fff" />
                    <rect x="15" y="70" width="15" height="15" fill="#fff" />
                    <rect x="18" y="18" width="9" height="9" fill="#07c160" />
                    <rect x="73" y="18" width="9" height="9" fill="#07c160" />
                    <rect x="18" y="73" width="9" height="9" fill="#07c160" />
                    <rect x="45" y="20" width="10" height="10" fill="#333" />
                    <rect x="40" y="45" width="20" height="10" fill="#333" />
                    <rect x="20" y="45" width="10" height="10" fill="#333" />
                    <rect x="70" y="45" width="10" height="20" fill="#333" />
                    <rect x="45" y="70" width="20" height="15" fill="#333" />
                  </svg>
                </div>
                <div className={styles.qrcodeTip}>
                  <span>💬 请使用微信扫一扫，扫描二维码完成支付</span>
                </div>
                <button
                  type="button"
                  className={styles.btnPaySubmit}
                  disabled={paying}
                  onClick={handlePay}
                >
                  {paying ? '正在模拟支付...' : '模拟微信扫码成功'}
                </button>
              </div>
            )}

            {activeTab === 'alipay' && (
              <div className={styles.qrcodeArea}>
                <div className={styles.qrcodeBox}>
                  {/* 内联 SVG 支付宝二维码 */}
                  <svg viewBox="0 0 100 100" className={styles.qrcodeImg}>
                    <rect width="100" height="100" fill="#fff" />
                    <rect x="10" y="10" width="25" height="25" fill="#1677ff" />
                    <rect x="65" y="10" width="25" height="25" fill="#1677ff" />
                    <rect x="10" y="65" width="25" height="25" fill="#1677ff" />
                    <rect x="15" y="15" width="15" height="15" fill="#fff" />
                    <rect x="70" y="15" width="15" height="15" fill="#fff" />
                    <rect x="15" y="70" width="15" height="15" fill="#fff" />
                    <rect x="18" y="18" width="9" height="9" fill="#1677ff" />
                    <rect x="73" y="18" width="9" height="9" fill="#1677ff" />
                    <rect x="18" y="73" width="9" height="9" fill="#1677ff" />
                    <rect x="45" y="15" width="10" height="20" fill="#333" />
                    <rect x="40" y="45" width="20" height="15" fill="#333" />
                    <rect x="25" y="45" width="8" height="10" fill="#333" />
                    <rect x="68" y="45" width="15" height="15" fill="#333" />
                    <rect x="45" y="70" width="20" height="10" fill="#333" />
                  </svg>
                </div>
                <div className={styles.qrcodeTip}>
                  <span>🔷 请使用支付宝扫一扫，扫描二维码完成支付</span>
                </div>
                <button
                  type="button"
                  className={styles.btnPaySubmit}
                  disabled={paying}
                  onClick={handlePay}
                >
                  {paying ? '正在模拟支付...' : '模拟支付宝扫码成功'}
                </button>
              </div>
            )}
          </div>
        </div>

        {/* 支付成功弹窗 */}
        <Modal
          open={paySuccess}
          footer={null}
          closable={false}
          centered
        >
          <Result
            status="success"
            title="支付成功！"
            subTitle={`订单已进入出库拣货流程，支付金额 ¥${finalAmount.toFixed(2)}。京东物流将准时为您送达。`}
            extra={[
              <button
                key="order"
                type="button"
                onClick={() => navigate({ to: '/user/order' })}
                style={{
                  background: '#e1251b',
                  color: '#fff',
                  border: 'none',
                  padding: '8px 24px',
                  borderRadius: '4px',
                  cursor: 'pointer',
                  marginRight: '12px',
                  fontWeight: 'bold',
                }}
              >
                查看订单详情
              </button>,
              <button
                key="home"
                type="button"
                onClick={() => navigate({ to: '/' })}
                style={{
                  background: '#fff',
                  color: '#666',
                  border: '1px solid #ddd',
                  padding: '8px 20px',
                  borderRadius: '4px',
                  cursor: 'pointer',
                }}
              >
                返回商城首页
              </button>,
            ]}
          />
        </Modal>
      </main>
    </TradeLayout>
  )
}

export default PayCashierPage
