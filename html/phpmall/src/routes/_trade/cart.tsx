import React, { useState } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import { Modal, message, Empty } from 'antd'
import { useCartStore } from '../../stores/cartStore'
import styles from './cart.module.css'

export const Route = createFileRoute('/_trade/cart')({
  component: CartPage,
})

const GUESS_ITEMS = [
  {
    id: 901,
    title: 'Apple Watch Ultra 智能运动户外手表 钛金属',
    price: 4999,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=200&q=80',
  },
  {
    id: 902,
    title: 'Sony WH-1000XM5 无线降噪头戴耳机 黑色',
    price: 1899,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80',
  },
  {
    id: 903,
    title: '27英寸 4K 160Hz MiniLED 广色域专业显示器',
    price: 1899,
    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=200&q=80',
  },
  {
    id: 904,
    title: '深入理解计算机系统 + 算法导论 精装典藏版',
    price: 128,
    image: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=200&q=80',
  },
]

function CartPage() {
  const navigate = useNavigate()
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
    addItem,
  } = useCartStore()

  const [activeTab, setActiveTab] = useState(0)
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
        message.success('已批量删除所选商品')
      },
    })
  }

  const handleAddGuess = (item: (typeof GUESS_ITEMS)[0]) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: '官方标配',
      shopName: '京东自营旗舰店',
    })
    message.success(`已将「${item.title}」加入购物车！`)
  }

  return (
    <main className={`w ${styles.cartMainContent}`}>
        {/* 顶部状态选项卡 */}
        <div className={styles.cartTabsBar}>
          <div
            className={`${styles.tabItem} ${activeTab === 0 ? styles.active : ''}`}
            onClick={() => setActiveTab(0)}
          >
            全部商品 <span className={styles.tabNum}>{items.length}</span>
          </div>
          <div
            className={`${styles.tabItem} ${activeTab === 1 ? styles.active : ''}`}
            onClick={() => setActiveTab(1)}
          >
            降价商品 <span className={styles.tabNum}>1</span>
          </div>
          <div
            className={`${styles.tabItem} ${activeTab === 2 ? styles.active : ''}`}
            onClick={() => setActiveTab(2)}
          >
            库存紧张 <span className={styles.tabNum}>0</span>
          </div>
          <div className={styles.deliverTip}>
            <span>配送至：<strong>北京市朝阳区</strong></span>
          </div>
        </div>

        {items.length === 0 ? (
          <div style={{ backgroundColor: '#fff', padding: '60px 0', textAlign: 'center', marginTop: '16px', borderRadius: '4px' }}>
            <Empty description="购物车空空如也，快去选购心仪的商品吧！" />
            <Link
              to="/"
              style={{
                display: 'inline-block',
                marginTop: '16px',
                background: '#e1251b',
                color: '#fff',
                padding: '8px 24px',
                borderRadius: '4px',
                textDecoration: 'none',
                fontSize: '13px',
              }}
            >
              去商城逛逛
            </Link>
          </div>
        ) : (
          <div className={styles.cartTable}>
            {/* 表头 */}
            <div className={styles.cartThead}>
              <div className={styles.thChk}>
                <label className={styles.customCheckbox}>
                  <input
                    type="checkbox"
                    checked={allSelected}
                    onChange={(e) => toggleSelectAll(e.target.checked)}
                  />
                  <span className={styles.chkBox}></span>
                  <span>全选</span>
                </label>
              </div>
              <div className={styles.thGoods}>商品清单</div>
              <div className={styles.thProps}>属性配置</div>
              <div className={styles.thPrice}>单价</div>
              <div className={styles.thQuantity}>数量</div>
              <div className={styles.thSum}>小计</div>
              <div className={styles.thOps}>操作</div>
            </div>

            {/* 店铺分组 */}
            <div className={styles.shopGroup}>
              <div className={styles.shopTitleBar}>
                <label className={styles.customCheckbox}>
                  <input
                    type="checkbox"
                    checked={allSelected}
                    onChange={(e) => toggleSelectAll(e.target.checked)}
                  />
                  <span className={styles.chkBox}></span>
                  <span className={styles.shopName}>
                    <span className={styles.badgeZy}>自营</span> 京东自营官方旗舰店
                  </span>
                </label>
                <span className={styles.freeShippingTag}>已免运费</span>
              </div>

              {/* 满减优惠横幅 */}
              <div className={styles.promoAlertBar}>
                <span className={styles.promoLabel}>满减</span>
                <span className={styles.promoDesc}>活动商品购满 300 元，即可立减 50 元现金（已减 50.00 元）</span>
                <Link to="/list" className={styles.goCoudan}>去凑单 &gt;</Link>
              </div>

              {/* 商品列表 */}
              {items.map((item) => (
                <div
                  key={item.id}
                  className={`${styles.cartRow} ${item.selected ? styles.selected : ''}`}
                >
                  <div className={styles.cellChk}>
                    <label className={styles.customCheckbox}>
                      <input
                        type="checkbox"
                        checked={item.selected}
                        onChange={() => toggleSelect(item.id)}
                      />
                      <span className={styles.chkBox}></span>
                    </label>
                  </div>
                  <div className={styles.cellGoods}>
                    <Link to="/item/$id" params={{ id: String(item.goodsId) }} className={styles.goodsImg}>
                      <img src={item.image} alt={item.title} />
                    </Link>
                    <div className={styles.goodsDetail}>
                      <Link to="/item/$id" params={{ id: String(item.goodsId) }} className={styles.goodsTitle}>
                        {item.title}
                      </Link>
                      <div className={styles.serviceTags}>
                        <span className={styles.tagZy}>京东物流</span>
                        <span className={styles.tagSafe}>选购安心保障</span>
                      </div>
                    </div>
                  </div>
                  <div className={styles.cellProps}>
                    <p>{item.sku || '官方正品标配'}</p>
                    <p style={{ color: '#bbb' }}>支持7天无理由退货</p>
                  </div>
                  <div className={styles.cellPrice}>
                    <p className={styles.curPrice}>¥{item.price.toFixed(2)}</p>
                    <p className={styles.origPrice}><del>¥{(item.price * 1.15).toFixed(2)}</del></p>
                  </div>
                  <div className={styles.cellQuantity}>
                    <div className={styles.stepper}>
                      <button
                        type="button"
                        className={styles.stepBtn}
                        disabled={item.quantity <= 1}
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                      >
                        -
                      </button>
                      <input
                        type="text"
                        className={styles.stepVal}
                        value={item.quantity}
                        readOnly
                      />
                      <button
                        type="button"
                        className={styles.stepBtn}
                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                      >
                        +
                      </button>
                    </div>
                    <p className={styles.stockTip}>有货</p>
                  </div>
                  <div className={styles.cellSum}>
                    <strong className={styles.sumPrice}>
                      ¥{(item.price * item.quantity).toFixed(2)}
                    </strong>
                  </div>
                  <div className={styles.cellOps}>
                    <button
                      type="button"
                      className={styles.btnOp}
                      onClick={() => handleDelete(item.id, item.title)}
                    >
                      删除
                    </button>
                    <button
                      type="button"
                      className={styles.btnOp}
                      onClick={() => message.success('已移入关注列表')}
                    >
                      移到关注
                    </button>
                  </div>
                </div>
              ))}
            </div>

            {/* 吸底结算浮动栏 */}
            <div className={styles.cartFloatbar}>
              <div className={styles.floatbarLeft}>
                <label className={styles.customCheckbox}>
                  <input
                    type="checkbox"
                    checked={allSelected}
                    onChange={(e) => toggleSelectAll(e.target.checked)}
                  />
                  <span className={styles.chkBox}></span>
                  <span>全选</span>
                </label>
                <button
                  type="button"
                  className={styles.barLinkBtn}
                  onClick={handleBatchDelete}
                >
                  删除选中的商品
                </button>
                <button
                  type="button"
                  className={styles.barLinkBtn}
                  onClick={() => message.info('已清除下架宝贝')}
                >
                  清除下架宝贝
                </button>
              </div>
              <div className={styles.floatbarRight}>
                <div className={styles.selectedAmount}>
                  已选择 <strong className={styles.hlCount}>{selectedCount}</strong> 件商品
                </div>
                <div className={styles.totalPriceBox}>
                  <div className={styles.totalRow}>
                    <span>总价（不含运费）：</span>
                    <strong className={styles.priceVal}>¥{finalPayAmount.toFixed(2)}</strong>
                  </div>
                  <div className={styles.discountRow}>
                    <span>已节省：-¥{discountAmount.toFixed(2)}</span>
                  </div>
                </div>
                <button
                  type="button"
                  className={styles.btnCheckout}
                  disabled={selectedCount === 0}
                  onClick={() => navigate({ to: '/checkout' })}
                >
                  去结算
                </button>
              </div>
            </div>
          </div>
        )}

        {/* 猜你喜欢推荐专区 */}
        <section className={styles.cartGuessLike}>
          <div className={styles.guessTitle}>
            <h3>猜你喜欢</h3>
          </div>
          <div className={styles.guessGrid}>
            {GUESS_ITEMS.map((g) => (
              <div key={g.id} className={styles.guessItem}>
                <img src={g.image} alt={g.title} className={styles.guessImg} />
                <div className={styles.guessInfo}>
                  <Link to="/item/$id" params={{ id: String(g.id) }} className={styles.guessItemTitle}>
                    {g.title}
                  </Link>
                  <div className={styles.guessPriceRow}>
                    <span className={styles.guessPrice}>¥{g.price.toFixed(2)}</span>
                    <button
                      type="button"
                      className={styles.btnGuessAdd}
                      onClick={() => handleAddGuess(g)}
                    >
                      + 加购
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </section>
      </main>
  )
}

export default CartPage
