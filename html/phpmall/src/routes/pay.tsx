import React, { useState } from 'react'
import { createFileRoute } from '@tanstack/react-router'
import { Card, Tabs, Radio, Button, Modal, Result, Tag, message } from 'antd'
import {
  CreditCardOutlined,
  WechatOutlined,
  AlipayOutlined,
  BankOutlined,
  CheckCircleOutlined,
  DownOutlined,
  UpOutlined,
} from '@ant-design/icons'
import { useCartStore } from '../stores/cartStore'
import { TradeHeader } from '../features/trade/components/TradeHeader'
import { MallFooter } from '../features/mall/components/MallFooter'

export const Route = createFileRoute('/pay')({
  component: PayCashierPage,
})

function PayCashierPage() {
  const { getFinalPayAmount, addresses, selectedAddressId, getSelectedItems } =
    useCartStore()

  const finalAmount = getFinalPayAmount() || 9597.0
  const selectedItems = getSelectedItems()
  const currentAddress =
    addresses.find((a) => a.id === selectedAddressId) || addresses[0]

  // 展开订单商品详情抽屉
  const [drawerOpen, setDrawerOpen] = useState(false)
  // 当前支付渠道
  const [activeTab, setActiveTab] = useState('baitiao')
  // 白条分期期数
  const [baitiaoPlan, setBaitiaoPlan] = useState('1')
  // 付款中
  const [paying, setPaying] = useState(false)
  // 支付成功 Modal
  const [paySuccess, setPaySuccess] = useState(false)

  const handlePay = () => {
    setPaying(true)
    setTimeout(() => {
      setPaying(false)
      setPaySuccess(true)
    }, 1000)
  }

  return (
    <div className="min-h-screen bg-[#f4f5f7]">
      <TradeHeader type="pay" />

      <main className="mx-auto max-w-[1200px] px-4 py-5">
        {/* 1. 订单摘要卡片 */}
        <div className="mb-4 flex items-center justify-between rounded-lg bg-white p-6 shadow-sm">
          <div className="flex items-start gap-4">
            <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#f6ffed] text-2xl text-[#52c41a]">
              <CheckCircleOutlined />
            </span>
            <div>
              <h3 className="text-base font-bold text-[rgba(0,0,0,0.88)]">
                订单提交成功，请尽快完成付款！
              </h3>
              <div className="mt-1 flex flex-wrap items-center gap-2 text-xs text-[#8c8c8c]">
                <span>
                  订单号：<strong className="text-[rgba(0,0,0,0.88)]">JD2026092889104</strong>
                </span>
                <span>|</span>
                <span>
                  收件人：{currentAddress?.name} ({currentAddress?.phone})
                </span>
                <span>|</span>
                <span>
                  地址：{currentAddress?.province} {currentAddress?.city}{' '}
                  {currentAddress?.detailAddress}
                </span>
              </div>
            </div>
          </div>

          <div className="flex items-center gap-6">
            <div className="text-right">
              <span className="text-xs text-[#8c8c8c]">应付金额：</span>
              <span className="text-sm font-bold text-[#e1251b]">¥</span>
              <strong className="text-2xl font-black text-[#e1251b]">
                {finalAmount.toFixed(2)}
              </strong>
            </div>

            <Button
              type="link"
              size="small"
              onClick={() => setDrawerOpen(!drawerOpen)}
              className="text-xs"
            >
              订单详情 {drawerOpen ? <UpOutlined /> : <DownOutlined />}
            </Button>
          </div>
        </div>

        {/* 展开的订单商品清单抽屉 */}
        {drawerOpen && (
          <div className="mb-4 rounded-lg border border-[#f0f0f0] bg-white p-5 shadow-sm animate-in fade-in duration-200">
            <h4 className="mb-3 text-xs font-semibold text-[rgba(0,0,0,0.88)]">
              购买商品明细 ({selectedItems.length} 件)
            </h4>
            <div className="space-y-3">
              {selectedItems.map((item) => (
                <div key={item.id} className="flex items-center justify-between text-xs">
                  <div className="flex items-center gap-3">
                    <img
                      src={item.imageUrl}
                      alt={item.title}
                      className="h-10 w-10 rounded border border-[#f0f0f0] object-cover"
                    />
                    <div>
                      <p className="line-clamp-1 text-[rgba(0,0,0,0.88)]">{item.title}</p>
                      <p className="text-[11px] text-[#8c8c8c]">{item.sku}</p>
                    </div>
                  </div>
                  <div className="font-medium text-[rgba(0,0,0,0.88)]">
                    ¥{item.price.toFixed(2)} × {item.quantity}
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* 2. 支付方式选择面板 */}
        <Card className="border-[#f0f0f0] shadow-sm">
          <Tabs
            activeKey={activeTab}
            onChange={setActiveTab}
            items={[
              {
                key: 'baitiao',
                label: (
                  <span className="flex items-center gap-1.5 text-sm">
                    <CreditCardOutlined className="text-base text-[#1677ff]" />
                    京东白条 (免息分期)
                  </span>
                ),
                children: (
                  <div className="py-4">
                    <p className="mb-3 text-xs font-semibold text-[rgba(0,0,0,0.88)]">
                      选择分期期数（享银牌免息特权）：
                    </p>
                    <Radio.Group
                      value={baitiaoPlan}
                      onChange={(e) => setBaitiaoPlan(e.target.value)}
                      className="grid grid-cols-1 gap-3 sm:grid-cols-3"
                    >
                      <Radio.Button
                        value="1"
                        className="flex h-16 items-center justify-between rounded-lg p-3 text-left"
                      >
                        <div>
                          <p className="font-bold text-[rgba(0,0,0,0.88)]">不分期 (下月还款)</p>
                          <p className="text-[11px] text-[#8c8c8c]">
                            应还 ¥{finalAmount.toFixed(2)} /期
                          </p>
                        </div>
                        <Tag color="green">0 服务费</Tag>
                      </Radio.Button>

                      <Radio.Button
                        value="3"
                        className="flex h-16 items-center justify-between rounded-lg p-3 text-left"
                      >
                        <div>
                          <p className="font-bold text-[rgba(0,0,0,0.88)]">分 3 期</p>
                          <p className="text-[11px] text-[#8c8c8c]">
                            ¥{(finalAmount / 3).toFixed(2)} × 3期
                          </p>
                        </div>
                        <Tag color="orange">免息优惠</Tag>
                      </Radio.Button>

                      <Radio.Button
                        value="6"
                        className="flex h-16 items-center justify-between rounded-lg p-3 text-left"
                      >
                        <div>
                          <p className="font-bold text-[rgba(0,0,0,0.88)]">分 6 期</p>
                          <p className="text-[11px] text-[#8c8c8c]">
                            ¥{(finalAmount / 6).toFixed(2)} × 6期
                          </p>
                        </div>
                        <Tag color="orange">免息优惠</Tag>
                      </Radio.Button>
                    </Radio.Group>
                  </div>
                ),
              },
              {
                key: 'wechat',
                label: (
                  <span className="flex items-center gap-1.5 text-sm">
                    <WechatOutlined className="text-base text-[#52c41a]" />
                    微信支付
                  </span>
                ),
                children: (
                  <div className="flex flex-col items-center py-6 text-center">
                    <div className="flex h-44 w-44 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white p-2 shadow-inner">
                      <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=weixin://wxpay/bizpayurl?pr=JD2026PAY"
                        alt="微信支付二维码"
                        className="h-full w-full object-contain"
                      />
                    </div>
                    <p className="mt-3 text-xs text-[#595959]">
                      请打开手机微信 扫一扫 扫描上方二维码完成支付
                    </p>
                  </div>
                ),
              },
              {
                key: 'alipay',
                label: (
                  <span className="flex items-center gap-1.5 text-sm">
                    <AlipayOutlined className="text-base text-[#1677ff]" />
                    支付宝
                  </span>
                ),
                children: (
                  <div className="flex flex-col items-center py-6 text-center">
                    <div className="flex h-44 w-44 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white p-2 shadow-inner">
                      <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=https://qr.alipay.com/bax0092892jdpay"
                        alt="支付宝二维码"
                        className="h-full w-full object-contain"
                      />
                    </div>
                    <p className="mt-3 text-xs text-[#595959]">
                      请打开手机支付宝 扫一扫 完成支付
                    </p>
                  </div>
                ),
              },
              {
                key: 'bank',
                label: (
                  <span className="flex items-center gap-1.5 text-sm">
                    <BankOutlined className="text-base text-[#722ed1]" />
                    银行卡快捷支付
                  </span>
                ),
                children: (
                  <div className="space-y-3 py-4 text-xs">
                    <div className="flex cursor-pointer items-center justify-between rounded border border-[#f0f0f0] p-3 hover:border-[#1677ff]">
                      <span className="font-semibold">招商银行 储蓄卡 (尾号 6829)</span>
                      <Tag color="blue">快捷支付</Tag>
                    </div>
                    <div className="flex cursor-pointer items-center justify-between rounded border border-[#f0f0f0] p-3 hover:border-[#1677ff]">
                      <span className="font-semibold">中国工商银行 信用卡 (尾号 1042)</span>
                      <Tag color="cyan">单笔限额 50,000</Tag>
                    </div>
                  </div>
                ),
              },
            ]}
          />

          <div className="mt-6 flex justify-end border-t border-[#f0f0f0] pt-4">
            <Button
              type="primary"
              size="large"
              loading={paying}
              onClick={handlePay}
              className="h-11 rounded-md bg-[#e1251b] px-10 text-base font-bold hover:bg-[#cf1322]"
            >
              立即支付 ¥{finalAmount.toFixed(2)}
            </Button>
          </div>
        </Card>
      </main>

      {/* 支付成功 Modal */}
      <Modal
        open={paySuccess}
        footer={null}
        closable={false}
        width={480}
        centered
      >
        <Result
          status="success"
          title="支付成功！"
          subTitle={`订单编号：JD2026092889104，实付金额 ¥${finalAmount.toFixed(2)}。商家正在飞速备货出库，请注意查收快递通知。`}
          extra={[
            <Button type="primary" key="orders" href="/seller" className="bg-[#1677ff]">
              前往商家工作台履约发货
            </Button>,
            <Button key="cart" href="/cart">
              返回购物车
            </Button>,
          ]}
        />
      </Modal>

      <MallFooter />
    </div>
  )
}
