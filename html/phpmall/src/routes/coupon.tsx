import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { message } from 'antd'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'
import styles from './coupon.module.css'

export const Route = createFileRoute('/coupon')({
  component: CouponCenterPage,
})

interface Coupon {
  id: string
  amount: number
  threshold: number
  title: string
  scope: string
  expiry: string
  progress: number
  category: 'digital' | 'phone' | 'beauty' | 'food'
  claimed?: boolean
}

const INITIAL_COUPONS: Coupon[] = [
  {
    id: 'c1',
    amount: 100,
    threshold: 1000,
    title: '数码家电跨店满减东券',
    scope: '全品类通用',
    expiry: '有效期至 2026.10.05',
    progress: 82,
    category: 'digital',
  },
  {
    id: 'c2',
    amount: 300,
    threshold: 3000,
    title: '高端智能手机专属补贴券',
    scope: '限 iPhone / 华为 指定型号',
    expiry: '有效期至 2026.10.03',
    progress: 95,
    category: 'phone',
  },
  {
    id: 'c3',
    amount: 50,
    threshold: 299,
    title: '大牌美妆护肤满减券',
    scope: '美妆自营旗舰店可用',
    expiry: '有效期至 2026.10.07',
    progress: 60,
    category: 'beauty',
  },
  {
    id: 'c4',
    amount: 30,
    threshold: 199,
    title: '京东超市生鲜食品券',
    scope: '生鲜食品全品类',
    expiry: '有效期至 2026.10.04',
    progress: 74,
    category: 'food',
  },
  {
    id: 'c5',
    amount: 500,
    threshold: 4999,
    title: '高配游戏本专属神券',
    scope: '限 RTX40系 游戏笔电',
    expiry: '有效期至 2026.10.02',
    progress: 88,
    category: 'digital',
  },
  {
    id: 'c6',
    amount: 20,
    threshold: 99,
    title: '京东健康保健滋补立减券',
    scope: '医药保健全场可用',
    expiry: '有效期至 2026.10.10',
    progress: 45,
    category: 'food',
  },
]

const TABS = [
  { key: 'all', label: '精选神券' },
  { key: 'phone', label: '手机数码' },
  { key: 'digital', label: '电脑办公' },
  { key: 'beauty', label: '美妆个护' },
  { key: 'food', label: '食品生鲜' },
]

function CouponCenterPage() {
  const [activeTab, setActiveTab] = useState('all')
  const [coupons, setCoupons] = useState(INITIAL_COUPONS)

  const handleClaim = (id: string, title: string) => {
    setCoupons((prev) =>
      prev.map((c) => (c.id === id ? { ...c, claimed: true, progress: Math.min(100, c.progress + 1) } : c))
    )
    message.success(`恭喜！已成功领取「${title}」！`)
  }

  const filteredCoupons = coupons.filter((c) => {
    if (activeTab === 'all') return true
    return c.category === activeTab
  })

  return (
    <div className={styles.couponBody}>
      {/* 顶部快捷条 */}
      <MallShortcutNav />

      {/* 领券专场大Banner */}
      <header className={styles.couponHeaderBanner}>
        <div className={`w ${styles.cHeadInner}`}>
          <div className={styles.cBrand}>
            <h1>🎟️ 京东领券中心</h1>
            <p>好券天天抢 · 大额满减补贴 · 全网神券一网打尽</p>
          </div>
          <div className={styles.cHeadActions}>
            <Link to="/user" className={styles.btnMyCoupons}>
              我的优惠券 (3)
            </Link>
          </div>
        </div>
      </header>

      {/* 分类吸顶条 */}
      <div className={styles.couponCatNav}>
        <div className={`w ${styles.cCatInner}`}>
          {TABS.map((tab) => (
            <div
              key={tab.key}
              className={`${styles.cCatTab} ${activeTab === tab.key ? styles.active : ''}`}
              onClick={() => setActiveTab(tab.key)}
            >
              {tab.label}
            </div>
          ))}
        </div>
      </div>

      {/* 锯齿券卡片网格 */}
      <main className={`w ${styles.couponGrid}`}>
        {filteredCoupons.map((coupon) => (
          <div key={coupon.id} className={styles.couponTicketCard}>
            <div className={styles.ticketLeft}>
              <div>
                <div className={styles.valRow}>
                  <span className={styles.yenSign}>¥</span>
                  <span className={styles.ticketAmount}>{coupon.amount}</span>
                  <span className={styles.thresholdText}>满 {coupon.threshold} 可用</span>
                </div>
                <h4 className={styles.ticketTitle}>{coupon.title}</h4>
                <p className={styles.ticketScope}>{coupon.scope}</p>
              </div>
              <p className={styles.ticketExpiry}>{coupon.expiry}</p>
            </div>

            <div className={styles.ticketRight}>
              <div className={styles.progressWrap}>
                <div className={styles.progressBar}>
                  <div
                    className={styles.progressFill}
                    style={{ width: `${coupon.progress}%` }}
                  />
                </div>
                <span className={styles.progressTxt}>已抢 {coupon.progress}%</span>
              </div>
              <button
                type="button"
                className={`${styles.btnClaim} ${coupon.claimed ? styles.claimed : ''}`}
                disabled={coupon.claimed}
                onClick={() => handleClaim(coupon.id, coupon.title)}
              >
                {coupon.claimed ? '已领取' : '立即领取'}
              </button>
            </div>
          </div>
        ))}
      </main>

      {/* 底部页脚 */}
      <MallFooter />
    </div>
  )
}

export default CouponCenterPage
