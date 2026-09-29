import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { message } from 'antd'
import { HeartFilled, HeartOutlined, CustomerServiceOutlined } from '@ant-design/icons'
import { useCartStore } from '../../stores/cartStore'
import styles from './shop.module.css'

export const Route = createFileRoute('/_mall/shop')({
  component: BrandShopPage,
})

const TOP_ITEMS = [
  {
    rank: 'TOP 1',
    rankClass: styles.top1,
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
    price: '¥7999.00',
    rawPrice: 7999,
    sales: '已售 50万+ 件',
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=300&q=80',
    sku: '原色钛金属 / 256GB',
  },
  {
    rank: 'TOP 2',
    rankClass: styles.top2,
    id: 3,
    title: 'Apple Watch Ultra 2 智能运动户外手表 49毫米钛金属',
    price: '¥5899.00',
    rawPrice: 5899,
    sales: '已售 12万+ 件',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=300&q=80',
    sku: '49毫米钛金属 / 越野回环',
  },
  {
    rank: 'TOP 3',
    rankClass: styles.top3,
    id: 6,
    title: 'Apple MacBook Air 13.6英寸 M3芯片 16G+512G 深空灰',
    price: '¥9999.00',
    rawPrice: 9999,
    sales: '已售 8.8万+ 件',
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&q=80',
    sku: 'M3芯片 / 16G+512G',
  },
  {
    rank: 'TOP 4',
    rankClass: '',
    id: 2,
    title: 'Apple AirPods Max 无线头戴式主动降噪耳机 银色',
    price: '¥3999.00',
    rawPrice: 3999,
    sales: '已售 6.5万+ 件',
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&q=80',
    sku: '银色 / 官方标配',
  },
]

const ALL_PRODUCTS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属',
    price: '¥7999.00',
    rawPrice: 7999,
    comment: '50万+条评价 · 98%好评',
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=360&q=80',
    sku: '原色钛金属 / 256GB',
  },
  {
    id: 6,
    title: 'Apple MacBook Air 13.6英寸 M3芯片 16G+512G',
    price: '¥9999.00',
    rawPrice: 9999,
    comment: '8.8万+条评价 · 99%好评',
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=360&q=80',
    sku: 'M3芯片 / 16G+512G',
  },
  {
    id: 7,
    title: 'Apple iPad Pro 11英寸 Ultra Retina XDR OLED屏',
    price: '¥8999.00',
    rawPrice: 8999,
    comment: '15万+条评价 · 98%好评',
    image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=360&q=80',
    sku: '11英寸 / 256GB / 深空灰',
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 2 智能运动户外腕表 49毫米',
    price: '¥5899.00',
    rawPrice: 5899,
    comment: '12万+条评价 · 99%好评',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=360&q=80',
    sku: '49毫米钛金属 / 蓝色海洋',
  },
]

function BrandShopPage() {
  const [isFollowed, setIsFollowed] = useState(false)
  const [activeTab, setActiveTab] = useState('home')
  const [filterTab, setFilterTab] = useState('recommend')
  const addItem = useCartStore((state) => state.addItem)

  const handleBuy = (item: { id: number; title: string; rawPrice: number; image: string; sku: string }) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.rawPrice,
      quantity: 1,
      image: item.image,
      sku: item.sku,
      shopName: 'Apple产品京东自营旗舰店',
    })
    message.success(`已将「${item.title}」加入购物车！`)
  }

  return (
    <div className={styles.shopBody}>
      <div>
        {/* 品牌旗舰店专属头部 */}
        <header className={styles.shopHeader}>
          <div className={`w ${styles.shopHeaderInner}`}>
            {/* 店铺品牌认证与评分信息 */}
            <div className={styles.shopBrandInfo}>
              <div className={styles.brandAvatar}>
                <span></span>
              </div>
              <div className={styles.brandDetail}>
                <div className={styles.titleRow}>
                  <h1 className={styles.shopTitle}>Apple产品京东自营旗舰店</h1>
                  <span className={styles.badgeZy}>自营官方旗舰</span>
                </div>
                <div className={styles.shopScores}>
                  <span>商品评价：<strong>4.9 高</strong></span>
                  <span className={styles.dot}>|</span>
                  <span>物流时效：<strong>4.9 高</strong></span>
                  <span className={styles.dot}>|</span>
                  <span>售后服务：<strong>4.9 高</strong></span>
                  <span className={styles.dot}>|</span>
                  <span className={styles.fansCount}>粉丝数：<strong>4285.6万</strong></span>
                </div>
              </div>
            </div>

            {/* 右侧互动与店内搜索 */}
            <div className={styles.shopHeaderRight}>
              <div className={styles.shopActions}>
                <button
                  type="button"
                  onClick={() => {
                    setIsFollowed(!isFollowed)
                    message.success(isFollowed ? '已取消关注' : '已关注该店铺！')
                  }}
                  className={`${styles.btnShopFollow} ${isFollowed ? styles.followed : ''}`}
                >
                  {isFollowed ? <HeartFilled /> : <HeartOutlined />}
                  <span>{isFollowed ? '已关注' : '关注店铺'}</span>
                </button>
                <Link to="/chat" className={styles.btnShopIm}>
                  <CustomerServiceOutlined /> 联系客服
                </Link>
              </div>
              <div className={styles.shopSearchBar}>
                <input type="text" placeholder="在店内搜索商品..." />
                <button type="button" className={styles.btnShopSearch}>搜店内</button>
              </div>
            </div>
          </div>

          {/* 店铺专属导航栏 */}
          <nav className={styles.shopNavbar}>
            <div className={`w ${styles.shopNavInner}`}>
              <ul className={styles.shopNavList}>
                <li className={activeTab === 'home' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('home')}>店铺首页</button>
                </li>
                <li className={activeTab === 'all' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('all')}>全部商品</button>
                </li>
                <li className={activeTab === 'iphone' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('iphone')}>iPhone 专区</button>
                </li>
                <li className={activeTab === 'mac' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('mac')}>Mac 电脑</button>
                </li>
                <li className={activeTab === 'ipad' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('ipad')}>iPad 平板</button>
                </li>
                <li className={activeTab === 'watch' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('watch')}>Apple Watch</button>
                </li>
                <li className={activeTab === 'audio' ? styles.active : ''}>
                  <button type="button" onClick={() => setActiveTab('audio')}>AirPods 影音</button>
                </li>
              </ul>
            </div>
          </nav>
        </header>

        {/* 店铺巨幕海报 (Store Hero Banner) */}
        <section className={styles.shopHeroBanner}>
          <div className={`w ${styles.shopHeroContent}`}>
            <div className={styles.heroTextBox}>
              <span className={styles.heroTag}>官方旗舰首发</span>
              <h2>iPhone 16 Pro 钛金属</h2>
              <p>A18 Pro 芯片，超强性能跃升。4800万微距超广角，捕捉惊艳瞬间。</p>
              <div className={styles.heroBtns}>
                <Link to="/item/$id" params={{ id: '1' }} className={styles.btnHeroBuy}>
                  立即选购 ›
                </Link>
                <button
                  type="button"
                  onClick={() => setActiveTab('all')}
                  className={styles.btnHeroMore}
                >
                  了解更多产品
                </button>
              </div>
            </div>
            <img
              src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&q=80"
              alt="iPhone 16 Pro"
              className={styles.heroImg}
            />
          </div>
        </section>

        {/* 店铺专属优惠券领取横幅 */}
        <section className={`w ${styles.shopCouponsBar}`}>
          <div className={styles.couponBannerCard}>
            <div className={styles.cpItem}>
              <div className={styles.cpLeft}>
                <span className={styles.yen}>¥</span><span className={styles.val}>400</span>
              </div>
              <div className={styles.cpMid}>
                <p className={styles.cond}>满 5000 元可用</p>
                <p className={styles.desc}>iPhone 16 系列专享券</p>
              </div>
              <button
                type="button"
                onClick={() => message.success('领取成功！下单结算时将自动抵扣。')}
                className={styles.btnGetCoupon}
              >
                立即领取
              </button>
            </div>

            <div className={styles.cpItem}>
              <div className={styles.cpLeft}>
                <span className={styles.yen}>¥</span><span className={styles.val}>200</span>
              </div>
              <div className={styles.cpMid}>
                <p className={styles.cond}>满 3000 元可用</p>
                <p className={styles.desc}>iPad / Watch 专享券</p>
              </div>
              <button
                type="button"
                onClick={() => message.success('领取成功！下单结算时将自动抵扣。')}
                className={styles.btnGetCoupon}
              >
                立即领取
              </button>
            </div>

            <div className={styles.cpItem}>
              <div className={styles.cpLeft}>
                <span className={styles.yen}>¥</span><span className={styles.val}>50</span>
              </div>
              <div className={styles.cpMid}>
                <p className={styles.cond}>满 500 元可用</p>
                <p className={styles.desc}>原装配件专享券</p>
              </div>
              <button
                type="button"
                onClick={() => message.success('领取成功！下单结算时将自动抵扣。')}
                className={styles.btnGetCoupon}
              >
                立即领取
              </button>
            </div>
          </div>
        </section>

        {/* 爆款热卖榜 (Store Best Sellers) */}
        <section className={`w ${styles.shopBestsellerSection}`}>
          <div className={styles.sectionHeaderRow}>
            <h3 className={styles.secTitle}>镇店爆款排行</h3>
            <span className={styles.secSub}>旗舰精选 · 亿万用户的品质信赖之选</span>
          </div>

          <div className={styles.bestsellerGrid}>
            {TOP_ITEMS.map((item) => (
              <div key={item.id} className={styles.topItem}>
                <span className={`${styles.rankBadge} ${item.rankClass}`}>{item.rank}</span>
                <Link to="/item/$id" params={{ id: String(item.id) }} className={styles.topThumb}>
                  <img src={item.image} alt={item.title} />
                </Link>
                <div className={styles.topInfo}>
                  <Link
                    to="/item/$id"
                    params={{ id: String(item.id) }}
                    className={styles.title}
                    title={item.title}
                  >
                    {item.title}
                  </Link>
                  <p className={styles.price}>{item.price}</p>
                  <span className={styles.sales}>{item.sales}</span>
                  <Link
                    to="/item/$id"
                    params={{ id: String(item.id) }}
                    className={styles.btnBuyLink}
                  >
                    立即抢购
                  </Link>
                </div>
              </div>
            ))}
          </div>
        </section>

        {/* 全部商品陈列与过滤 (All Products) */}
        <main className={`w ${styles.shopAllGoods}`}>
          <div className={styles.goodsFilterBar}>
            <div className={styles.filterLeft}>
              <button
                type="button"
                onClick={() => setFilterTab('recommend')}
                className={`${styles.fBtn} ${filterTab === 'recommend' ? styles.fBtnActive : ''}`}
              >
                综合推荐
              </button>
              <button
                type="button"
                onClick={() => setFilterTab('sales')}
                className={`${styles.fBtn} ${filterTab === 'sales' ? styles.fBtnActive : ''}`}
              >
                销量最高
              </button>
              <button
                type="button"
                onClick={() => setFilterTab('new')}
                className={`${styles.fBtn} ${filterTab === 'new' ? styles.fBtnActive : ''}`}
              >
                新品上市
              </button>
              <button
                type="button"
                onClick={() => setFilterTab('price')}
                className={`${styles.fBtn} ${filterTab === 'price' ? styles.fBtnActive : ''}`}
              >
                价格 ↕
              </button>
            </div>
            <div className={styles.countTip}>
              共找到 <strong>{ALL_PRODUCTS.length}</strong> 件店内在售正品商品
            </div>
          </div>

          <div className={styles.shopGoodsGrid}>
            {ALL_PRODUCTS.map((prod) => (
              <div key={prod.id} className={styles.sCard}>
                <Link to="/item/$id" params={{ id: String(prod.id) }} className={styles.sThumb}>
                  <img src={prod.image} alt={prod.title} />
                </Link>
                <div className={styles.sMeta}>
                  <p className={styles.sPrice}>{prod.price}</p>
                  <Link
                    to="/item/$id"
                    params={{ id: String(prod.id) }}
                    className={styles.sTitle}
                    title={prod.title}
                  >
                    <span className={styles.badgeZy}>自营</span> {prod.title}
                  </Link>
                  <p className={styles.sComment}>{prod.comment}</p>
                  <button
                    type="button"
                    onClick={() => handleBuy(prod)}
                    className={styles.btnShopAddCart}
                  >
                    加入购物车
                  </button>
                </div>
              </div>
            ))}
          </div>
        </main>
      </div>
    </div>
  )
}
