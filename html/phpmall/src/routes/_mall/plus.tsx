import React from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Button, message } from 'antd'
import { CrownFilled, ShoppingCartOutlined } from '@ant-design/icons'
import { useCartStore } from '../../stores/cartStore'
import styles from './plus.module.css'

export const Route = createFileRoute('/_mall/plus')({
  component: PlusMemberPage,
})

const PRIVILEGES = [
  { icon: '📦', title: '无限免邮', desc: '自营商品全年不限次免运费' },
  { icon: '🪙', title: '10倍京豆', desc: '购物享最高10倍京豆返利' },
  { icon: '🏷️', title: '专享95折', desc: '百万精选大牌商品折上再折' },
  { icon: '🎟️', title: '每月百元券', desc: '全品类无门槛通用优惠券' },
  { icon: '🎧', title: '24h专属客服', desc: '白金专属通道一秒接通' },
  { icon: '🎬', title: '生活特权联名', desc: '免费畅看爱奇艺/腾讯影视会员' },
]

const PLUS_GOODS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
    plusPrice: 7599,
    normalPrice: 7999,
    save: 'PLUS立省400',
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400&q=80',
    sku: '原色钛金属 / 256GB',
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 高解析度头戴无线降噪耳机',
    plusPrice: 1799,
    normalPrice: 1899,
    save: 'PLUS专享95折',
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
    sku: '黑色 / 官方标配',
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能运动户外手表 49mm 钛金属',
    plusPrice: 4799,
    normalPrice: 4999,
    save: 'PLUS直降200',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&q=80',
    sku: '49mm钛金属 / 高山回环',
  },
  {
    id: 4,
    title: '极米（XGIMI）H6 4K光学变焦版 智能家用投影机',
    plusPrice: 5699,
    normalPrice: 6299,
    save: 'PLUS立省600',
    image: 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=400&q=80',
    sku: '4K超清 / 光学变焦',
  },
]

function PlusMemberPage() {
  const addItem = useCartStore((state) => state.addItem)

  const handleBuy = (item: (typeof PLUS_GOODS)[0]) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.plusPrice,
      quantity: 1,
      image: item.image,
      sku: item.sku,
      shopName: '京东自营官方旗舰店',
    })
    message.success(`已按 PLUS 会员专享价 ¥${item.plusPrice} 加入购物车！`)
  }

  return (
    <div className={styles.plusBody}>
      <div>
        {/* PLUS 专属黑金 Header */}
        <header className={styles.plusHeader}>
          <div className={`w ${styles.plusHeaderInner}`}>
            <div className={styles.logoPlusTitle}>
              <Link to="/" className={styles.logoBox}>
                <span className={styles.logoText}>JD</span>
                <span className={styles.logoSub}>京东</span>
              </Link>
              <div className={styles.plusBrand}>
                <span className={styles.crownIcon}>👑</span>
                <h2 className={styles.pageTitle}>PLUS 会员俱乐部</h2>
              </div>
            </div>

            <div className={styles.plusQuickStats}>
              <span className={styles.statText}>
                已为您累计省下 <strong className={styles.goldNum}>¥1,280.00</strong>
              </span>
              <button
                type="button"
                onClick={() => message.success('已成功续费 PLUS 会员年卡（有效期顺延至 2028 年）')}
                className={styles.btnRenew}
              >
                立即续费
              </button>
            </div>
          </div>
        </header>

        {/* 黑金通栏首屏 Hero Showcase */}
        <section className={styles.plusHeroBanner}>
          <div className={`w ${styles.plusHeroCard}`}>
            {/* VIP 会员名片 */}
            <div className={styles.vipUserInfo}>
              <div className={styles.vipAvatar}>
                <img
                  src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&q=80"
                  alt="会员头像"
                />
                <span className={styles.crownBadge}>VIP</span>
              </div>

              <div className={styles.vipText}>
                <div className={styles.titleRow}>
                  <h3>尊敬的 PLUS 年卡会员 张三</h3>
                  <span className={styles.validTag}>有效期至 2027-09-28</span>
                </div>
                <p className={styles.vipSubtitle}>
                  您已享有 10 大尊贵特权，本月 100 元超级券包已自动发放到账
                </p>
              </div>
            </div>

            {/* 6大核心特权图标金刚区 */}
            <div className={styles.privilegeGrid}>
              {PRIVILEGES.map((p) => (
                <div key={p.title} className={styles.privItem}>
                  <div className={styles.privIcon}>{p.icon}</div>
                  <h4>{p.title}</h4>
                  <p>{p.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* PLUS 会员专属特价商品榜 */}
        <section className={`w ${styles.plusGoodsSection}`}>
          <div className={styles.plusSectionTitle}>
            <span className={styles.titleIcon}>💎</span>
            <h3>PLUS 会员专享价爆款榜</h3>
            <span className={styles.subText}>精选自营尖货 · 会员尊享超低折扣</span>
          </div>

          <div className={styles.plusGoodsGrid}>
            {PLUS_GOODS.map((g) => (
              <div key={g.id} className={styles.plusCard}>
                <Link to="/item/$id" params={{ id: String(g.id) }} className={styles.pThumb}>
                  <img src={g.image} alt={g.title} />
                  <span className={styles.plusCornerTag}>{g.save}</span>
                </Link>

                <div className={styles.pBody}>
                  <Link
                    to="/item/$id"
                    params={{ id: String(g.id) }}
                    className={styles.pTitle}
                    title={g.title}
                  >
                    {g.title}
                  </Link>

                  <div className={styles.pPrices}>
                    <div className={styles.plusPrice}>
                      <span className={styles.priceLabel}>PLUS价</span>
                      <span className={styles.yen}>¥</span>
                      <span className={styles.val}>{g.plusPrice}</span>
                    </div>
                    <span className={styles.normalPrice}>
                      原价 ¥{g.normalPrice}
                    </span>
                  </div>

                  <button
                    type="button"
                    onClick={() => handleBuy(g)}
                    className={styles.btnPlusBuy}
                  >
                    立即购买
                  </button>
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>
    </div>
  )
}
