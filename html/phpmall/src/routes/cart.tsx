import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import { Checkbox, Button, Tag, InputNumber, Empty, Modal, message } from 'antd'
import { DeleteOutlined, ShopOutlined, CheckCircleOutlined } from '@ant-design/icons'
import { useCartStore } from '../stores/cartStore'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { TradeHeader } from '../features/trade/components/TradeHeader'
import { MallFooter } from '../features/mall/components/MallFooter'

export const Route = createFileRoute('/cart')({
  component: CartPage,
})

function CartPage() {
  const {
    items,
    toggleSelect,
    toggleSelectAll,
    updateQuantity,
    removeItem,
    removeSelected,
    getSelectedCount,
    getTotalPrice,
    getDiscountAmount,
    getFinalPayAmount,
  } = useCartStore()

  const allSelected = items.length > 0 && items.every((i) => i.selected)
  const selectedCount = getSelectedCount()
  const totalPrice = getTotalPrice()
  const discountAmount = getDiscountAmount()
  const finalPayAmount = getFinalPayAmount()

  const handleDelete = (id: string, title: string) => {
    Modal.confirm({
      title: '确认删除商品？',
      content: `确定从购物车中删除【${title.slice(0, 18)}...】吗？`,
      okText: '删除',
      okType: 'danger',
      cancelText: '保留',
      onOk: () => {
        removeItem(id)
        message.success('商品已从购物车删除')
      },
    })
  }

  const handleBatchDelete = () => {
    if (selectedCount === 0) {
      message.warning('请先勾选需要删除的商品')
      return
    }
    Modal.confirm({
      title: '批量删除商品？',
      content: `确定从购物车中删除已选中的 ${selectedCount} 件商品吗？`,
      okText: '删除',
      okType: 'danger',
      cancelText: '保留',
      onOk: () => {
        removeSelected()
        message.success('已删除选中的商品')
      },
    })
  }

  return (
    <div className="min-h-screen bg-[#f4f5f7]">
      <MallShortcutNav />
      <TradeHeader type="cart" />

      <main className="mx-auto max-w-[1200px] px-4 py-5">
        {/* 顶部选项卡 */}
        <div className="mb-3 flex items-center justify-between border-b border-[#e5e7eb] bg-white px-5 py-3 text-sm">
          <div className="flex gap-6 font-medium">
            <span className="cursor-pointer font-bold text-[#e1251b] border-b-2 border-[#e1251b] pb-2">
              全部商品 ({items.length})
            </span>
            <span className="cursor-pointer text-[#666] hover:text-[#e1251b]">
              降价商品 (1)
            </span>
            <span className="cursor-pointer text-[#666] hover:text-[#e1251b]">
              库存紧张 (0)
            </span>
          </div>
          <div className="text-xs text-[#8c8c8c]">
            配送至：<strong className="text-[rgba(0,0,0,0.88)]">北京市朝阳区</strong>
          </div>
        </div>

        {items.length === 0 ? (
          <div className="rounded-lg bg-white p-12 text-center shadow-sm">
            <Empty description="购物车空空如也，快去选购心仪的商品吧！">
              <Button type="primary" href="/" className="mt-2 bg-[#e1251b]">
                去商城逛逛
              </Button>
            </Empty>
          </div>
        ) : (
          <div className="space-y-4">
            {/* 表头 */}
            <div className="grid grid-cols-12 items-center bg-[#f9fafb] px-4 py-2.5 text-xs text-[#666]">
              <div className="col-span-1 flex items-center gap-2">
                <Checkbox
                  checked={allSelected}
                  onChange={(e) => toggleSelectAll(e.target.checked)}
                >
                  全选
                </Checkbox>
              </div>
              <div className="col-span-5">商品清单</div>
              <div className="col-span-2 text-center">单价</div>
              <div className="col-span-2 text-center">数量</div>
              <div className="col-span-1 text-center">小计</div>
              <div className="col-span-1 text-right">操作</div>
            </div>

            {/* 自营满减横幅 */}
            <div className="flex items-center justify-between rounded bg-[#fff1f0] px-4 py-2 text-xs text-[#cf1322]">
              <div className="flex items-center gap-2">
                <Tag color="error" className="m-0">满减</Tag>
                <span>自营大促：购满 300 元立减 50 元现金（已满足减 ¥50.00）</span>
              </div>
              <a href="/list" className="text-[#cf1322] hover:underline">去凑单 &gt;</a>
            </div>

            {/* 商品清单列表 */}
            <div className="space-y-3">
              {items.map((item) => (
                <div
                  key={item.id}
                  className={`grid grid-cols-12 items-center rounded-lg border bg-white p-4 shadow-sm transition-all ${
                    item.selected ? 'border-[#ffccc7] bg-[#fffefe]' : 'border-[#f0f0f0]'
                  }`}
                >
                  <div className="col-span-1">
                    <Checkbox
                      checked={item.selected}
                      onChange={() => toggleSelect(item.id)}
                    />
                  </div>

                  <div className="col-span-5 flex items-center gap-3">
                    <img
                      src={item.imageUrl}
                      alt={item.title}
                      className="h-18 w-18 rounded border border-[#f0f0f0] object-cover"
                    />
                    <div className="space-y-1 text-xs">
                      <p className="line-clamp-2 font-medium text-[rgba(0,0,0,0.88)]">
                        {item.title}
                      </p>
                      <p className="text-[11px] text-[#8c8c8c]">{item.sku}</p>
                      <div className="flex gap-1">
                        {item.isSelfOperated && (
                          <Tag color="red" className="m-0 text-[10px]">
                            自营
                          </Tag>
                        )}
                        <Tag color="default" className="m-0 text-[10px]">
                          京准达
                        </Tag>
                      </div>
                    </div>
                  </div>

                  <div className="col-span-2 text-center text-xs font-semibold text-[rgba(0,0,0,0.88)]">
                    ¥{item.price.toFixed(2)}
                  </div>

                  <div className="col-span-2 flex justify-center">
                    <InputNumber
                      min={1}
                      max={99}
                      value={item.quantity}
                      onChange={(val) => {
                        if (val && val !== item.quantity) {
                          updateQuantity(item.id, val - item.quantity)
                        }
                      }}
                      size="small"
                    />
                  </div>

                  <div className="col-span-1 text-center text-xs font-bold text-[#e1251b]">
                    ¥{(item.price * item.quantity).toFixed(2)}
                  </div>

                  <div className="col-span-1 text-right">
                    <Button
                      type="text"
                      danger
                      icon={<DeleteOutlined />}
                      size="small"
                      onClick={() => handleDelete(item.id, item.title)}
                    />
                  </div>
                </div>
              ))}
            </div>

            {/* 吸底结算条 */}
            <div className="sticky bottom-0 z-50 flex items-center justify-between rounded-lg border border-[#e5e7eb] bg-white px-5 py-3 shadow-lg">
              <div className="flex items-center gap-4 text-xs text-[#666]">
                <Checkbox
                  checked={allSelected}
                  onChange={(e) => toggleSelectAll(e.target.checked)}
                >
                  全选
                </Checkbox>
                <button
                  type="button"
                  onClick={handleBatchDelete}
                  className="cursor-pointer text-[#666] hover:text-[#e1251b]"
                >
                  删除选中的商品
                </button>
                <button type="button" className="cursor-pointer text-[#666] hover:text-[#e1251b]">
                  移入关注
                </button>
              </div>

              <div className="flex items-center gap-6">
                <div className="text-right">
                  <div className="flex items-baseline gap-2">
                    <span className="text-xs text-[#666]">
                      已选 <strong className="text-[#e1251b]">{selectedCount}</strong> 件商品，总价：
                    </span>
                    <strong className="text-xl font-bold text-[#e1251b]">
                      ¥{finalPayAmount.toFixed(2)}
                    </strong>
                  </div>
                  {discountAmount > 0 && (
                    <p className="text-[11px] text-[#52c41a]">
                      促销已立减：-¥{discountAmount.toFixed(2)}
                    </p>
                  )}
                </div>

                <Button
                  type="primary"
                  size="large"
                  disabled={selectedCount === 0}
                  href="/checkout"
                  className="h-11 rounded-md bg-[#e1251b] px-8 text-base font-semibold hover:bg-[#cf1322]"
                >
                  去结算 ({selectedCount})
                </Button>
              </div>
            </div>
          </div>
        )}

        {/* 猜你喜欢推荐专区 */}
        <section className="mt-10 rounded-lg bg-white p-5 shadow-sm">
          <h3 className="mb-4 text-base font-bold text-[rgba(0,0,0,0.88)]">猜你喜欢</h3>
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
            {[
              {
                id: 'g1',
                title: '户外GPS智能运动腕表 钛合金轻量机身',
                price: 2999.0,
                img: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=200&q=80',
              },
              {
                id: 'g2',
                title: '竞速轻量碳板缓震全掌跑鞋 专业马拉松',
                price: 289.0,
                img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&q=80',
              },
              {
                id: 'g3',
                title: '27英寸 4K 160Hz MiniLED 电竞显示器',
                price: 1899.0,
                img: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=200&q=80',
              },
              {
                id: 'g4',
                title: '赋活水乳套装 清爽保湿温和滋润',
                price: 399.0,
                img: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=200&q=80',
              },
            ].map((g) => (
              <div
                key={g.id}
                className="group rounded border border-[#f0f0f0] p-3 text-center transition-all hover:shadow-md"
              >
                <img
                  src={g.img}
                  alt={g.title}
                  className="mx-auto h-32 w-32 object-cover transition-transform group-hover:scale-105"
                />
                <p className="mt-2 line-clamp-1 text-xs text-[rgba(0,0,0,0.88)]">{g.title}</p>
                <p className="mt-1 font-semibold text-[#e1251b]">¥{g.price.toFixed(2)}</p>
                <Button
                  size="small"
                  className="mt-2 w-full text-xs"
                  onClick={() => message.success('已加入购物车')}
                >
                  加入购物车
                </Button>
              </div>
            ))}
          </div>
        </section>
      </main>

      <MallFooter />
    </div>
  )
}
