import React, { useState, useEffect } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../stores/cartStore'
import styles from './seckill.module.css'

export const Route = createFileRoute('/_mall/seckill')({
  component: SeckillPage,
})

const SLOTS = [
  { time: '00:00', status: '已开抢' },
  { time: '08:00', status: '已开抢' },
  { time: '12:00', status: '抢购进行中', active: true },
  { time: '16:00', status: '即将开抢' },
  { time: '20:00', status: '即将开抢' },
]

const CATEGORIES = [
  { key: 'all', name: '全部' },
  { key: 'phone', name: '手机通讯' },
  { key: 'digital', name: '数码影音' },
  { key: 'appliance', name: '生活家电' },
  { key: 'food', name: '食品饮料' },
]

const SECKILL_ITEMS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
    category: 'phone',
    discount: '直降 ¥800',
    price: 7999,
    originalPrice: 8799,
    soldPercent: 88,
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400&q=80',
    sku: '原色钛金属 / 256GB',
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 高解析度头戴无线降噪耳机 经典黑',
    category: 'digital',
    discount: '限时 7.5 折',
    price: 1899,
    originalPrice: 2499,
    soldPercent: 75,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
    sku: '经典黑 / 官方标配',
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能运动户外手表 49mm 钛金属',
    category: 'digital',
    discount: '秒杀补贴',
    price: 4999,
    originalPrice: 6299,
    soldPercent: 92,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&q=80',
    sku: '49mm钛金属 / 高山回环',
  },
  {
    id: 4,
    title: '富士 X-T5 复古微单数码相机套机 (16-80mm镜头)',
    category: 'digital',
    discount: '爆款直降',
    price: 11999,
    originalPrice: 13499,
    soldPercent: 64,
    image: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&q=80',
    sku: '经典银黑 / 16-80套机',
  },
  {
    id: 5,
    title: '华为 Mate 60 Pro 12GB+512GB 雅川青 卫星通话手机',
    category: 'phone',
    discount: '政府补贴',
    price: 6999,
    originalPrice: 7499,
    soldPercent: 96,
    image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&q=80',
    sku: '雅川青 / 512GB',
  },
  {
    id: 6,
    title: '戴森 (Dyson) V12 Detect Slim 智能无绳吸尘器',
    category: 'appliance',
    discount: '满减 600',
    price: 3699,
    originalPrice: 4299,
    soldPercent: 58,
    image: 'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=400&q=80',
    sku: '金色 / 激光探污版',
  },
  {
    id: 7,
    title: '飞利浦 (PHILIPS) 钻石亮白智能声波电动牙刷',
    category: 'appliance',
    discount: '买一送一',
    price: 799,
    originalPrice: 1299,
    soldPercent: 84,
    image: 'https://images.unsplash.com/photo-1559591937-e10222460da4?w=400&q=80',
    sku: '优雅白 / 双刷头充电盒',
  },
  {
    id: 8,
    title: '无线三模客制化机械键盘 Gasket结构 87键',
    category: 'digital',
    discount: '首发立减',
    price: 349,
    originalPrice: 499,
    soldPercent: 70,
    image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&q=80',
    sku: '茶轴 / 黑色',
  },
]

function SeckillPage() {
  const [activeSlot, setActiveSlot] = useState('12:00')
  const [activeCat, setActiveCat] = useState('all')
  const [timeLeft, setTimeLeft] = useState({ hours: 1, minutes: 42, seconds: 18 })
  const addItem = useCartStore((state) => state.addItem)

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev.seconds > 0) return { ...prev, seconds: prev.seconds - 1 }
        if (prev.minutes > 0) return { ...prev, minutes: 59, seconds: 59 }
        if (prev.hours > 0) return { hours: prev.hours - 1, minutes: 59, seconds: 59 }
        return { hours: 2, minutes: 0, seconds: 0 }
      })
    }, 1000)
    return () => clearInterval(timer)
  }, [])

  const filteredItems = SECKILL_ITEMS.filter((item) => {
    if (activeCat === 'all') return true
    return item.category === activeCat
  })

  const handleBuy = (item: (typeof SECKILL_ITEMS)[0]) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: item.sku,
      shopName: '京东自营官方旗舰店',
    })
    message.success(`已将「${item.title}」成功加入购物车！`)
  }

  const formatDigit = (n: number) => String(n).padStart(2, '0')

  return (
    <div className={styles.seckillBody}>
      {/* 秒杀红金Header */}
      <header className={styles.seckillHeader}>
        <div className={`w ${styles.seckillHeaderInner}`}>
          <div className={styles.skBrandLeft}>
            <Link to="/seckill" className={styles.skLogoTag}>
              <div className={styles.skLogoIcon}>⚡</div>
              <h1 className={styles.skLogoTitle}>京东秒杀</h1>
            </Link>
            <span className={styles.skSubTag}>FLASH SALE · 正品低价 限时限量</span>
          </div>
          <div>
            <Link to="/" style={{ color: '#fff', fontSize: '13px', textDecoration: 'none' }}>
              返回商城首页 ›
            </Link>
          </div>
        </div>
      </header>

      {/* 场次吸顶导航 */}
      <div className={styles.seckillTimelineBar}>
        <div className={`w ${styles.skTimelineInner}`}>
          <div className={styles.timelineSlots}>
            {SLOTS.map((slot) => (
              <div
                key={slot.time}
                className={`${styles.timelineItem} ${activeSlot === slot.time ? styles.active : ''}`}
                onClick={() => setActiveSlot(slot.time)}
              >
                <span className={styles.timelineTime}>{slot.time}</span>
                <span className={styles.timelineState}>{slot.status}</span>
              </div>
            ))}
          </div>

          <div className={styles.timelineCountdown}>
            <span>距本场结束还剩：</span>
            <span className={styles.cdBox}>{formatDigit(timeLeft.hours)}</span>:
            <span className={styles.cdBox}>{formatDigit(timeLeft.minutes)}</span>:
            <span className={styles.cdBox}>{formatDigit(timeLeft.seconds)}</span>
          </div>
        </div>
      </div>

      {/* 品类选择条 */}
      <div className={`w ${styles.seckillCategoryBar}`}>
        <ul className={styles.catList}>
          {CATEGORIES.map((cat) => (
            <li
              key={cat.key}
              className={`${styles.catItem} ${activeCat === cat.key ? styles.active : ''}`}
              onClick={() => setActiveCat(cat.key)}
            >
              {cat.name}
            </li>
          ))}
        </ul>
      </div>

      {/* 秒杀商品网格 */}
      <main className={`w ${styles.seckillGrid}`}>
        {filteredItems.map((item) => (
          <div key={item.id} className={styles.seckillCard}>
            <div>
              <div className={styles.skImgWrap}>
                <img src={item.image} alt={item.title} />
                <span className={styles.skDiscountBadge}>{item.discount}</span>
              </div>
              <Link to="/item/$id" params={{ id: String(item.id) }} className={styles.skTitle}>
                {item.title}
              </Link>
              <div className={styles.skPriceRow}>
                <span className={styles.curPrice}>¥{item.price.toLocaleString()}</span>
                <span className={styles.origPrice}>¥{item.originalPrice.toLocaleString()}</span>
              </div>
              <div className={styles.skProgressWrap}>
                <div className={styles.progressBar}>
                  <div
                    className={styles.progressFill}
                    style={{ width: `${item.soldPercent}%` }}
                  />
                </div>
                <div className={styles.progressTxt}>
                  <span>已抢 {item.soldPercent}%</span>
                  <span>仅剩 {100 - item.soldPercent} 件</span>
                </div>
              </div>
            </div>
            <button
              type="button"
              className={styles.btnSkBuy}
              onClick={() => handleBuy(item)}
            >
              立即抢购
            </button>
          </div>
        ))}
      </main>
    </div>
  )
}

export default SeckillPage
