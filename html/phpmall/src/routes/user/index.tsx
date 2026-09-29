import React from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Avatar, Tag } from 'antd'
import { UserOutlined, CrownFilled } from '@ant-design/icons'
import { MallShortcutNav } from '../../features/mall/components/MallShortcutNav'
import { MallFooter } from '../../features/mall/components/MallFooter'
import { UserHeader } from '../../features/user/components/UserHeader'
import { UserSidebar } from '../../features/user/components/UserSidebar'
import styles from './index.module.css'

export const Route = createFileRoute('/user/')({
  component: UserDashboardPage,
})

function UserDashboardPage() {
  return (
    <div className={styles.userBody}>
      <div>
        <MallShortcutNav />
        <UserHeader title="我的京东 - 个人中心" />

        <div className={`w ${styles.userLayout}`}>
          <UserSidebar />

          {/* 右侧核心面板 */}
          <main className={styles.mainPanel}>
            {/* 用户全景名片卡 */}
            <div className={styles.userCard}>
              <div className={styles.userInfoLeft}>
                <Avatar
                  size={64}
                  src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&q=80"
                  icon={<UserOutlined />}
                  style={{ border: '2px solid #e1251b' }}
                />
                <div>
                  <div className={styles.userNameRow}>
                    <h3 className={styles.userName}>张三</h3>
                    <span className={styles.plusBadge}>
                      <CrownFilled style={{ color: '#2b1f06' }} /> PLUS正式年卡会员
                    </span>
                  </div>
                  <div className={styles.metaRow}>
                    <span>
                      小白信用: <strong style={{ color: '#52c41a' }}>102.5</strong> (极好)
                    </span>
                    <span>
                      京享值: <strong>12,850</strong>
                    </span>
                    <Tag color="success">实名已认证</Tag>
                  </div>
                </div>
              </div>

              {/* 资产四宫格 */}
              <div className={styles.assetGrid}>
                <div className={styles.assetItem}>
                  <div className={`${styles.assetVal} ${styles.assetValRed}`}>5</div>
                  <div className={styles.assetLabel}>优惠券</div>
                </div>
                <div className={styles.assetItem}>
                  <div className={styles.assetVal}>1,280</div>
                  <div className={styles.assetLabel}>京豆余额</div>
                </div>
                <div className={styles.assetItem}>
                  <div className={styles.assetVal}>¥20,000</div>
                  <div className={styles.assetLabel}>白条可用额度</div>
                </div>
              </div>
            </div>

            {/* 交易履约状态四徽章 */}
            <div className={styles.orderStatusGrid}>
              <Link to="/user/order" className={styles.orderStatusItem}>
                <div className={styles.statusIcon}>💳</div>
                <div className={styles.statusText}>待付款 (0)</div>
              </Link>
              <Link to="/user/order" className={styles.orderStatusItem}>
                <div className={styles.statusIcon}>🚚</div>
                <div className={styles.statusText}>
                  待收货 <span className={styles.numHighlight}>(1)</span>
                </div>
              </Link>
              <Link to="/user/order" className={styles.orderStatusItem}>
                <div className={styles.statusIcon}>⭐</div>
                <div className={styles.statusText}>
                  待评价 <span className={styles.numHighlight}>(1)</span>
                </div>
              </Link>
              <Link to="/user/order" className={styles.orderStatusItem}>
                <div className={styles.statusIcon}>🔄</div>
                <div className={styles.statusText}>返修/售后 (0)</div>
              </Link>
            </div>

            {/* 最近订单速览 */}
            <div className={styles.recentOrdersBlock}>
              <div className={styles.blockHead}>
                <h4 className={styles.blockTitle}>📦 近期订单速览</h4>
                <Link to="/user/order" className={styles.moreLink}>
                  查看全部订单 ›
                </Link>
              </div>

              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '12px 16px', background: '#fafbfc', border: '1px solid #eee', borderRadius: 4 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
                  <img
                    src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=80&q=80"
                    alt="iPhone 16 Pro"
                    style={{ width: 50, height: 50, objectFit: 'cover', borderRadius: 4 }}
                  />
                  <div>
                    <div style={{ fontSize: 13, fontWeight: 'bold', color: '#333' }}>
                      Apple iPhone 16 Pro 256GB 原色钛金属
                    </div>
                    <div style={{ fontSize: 12, color: '#888', marginTop: 4 }}>
                      订单号：JD2026092800101 · 2026-09-28 14:32:05
                    </div>
                  </div>
                </div>
                <div style={{ textAlign: 'right' }}>
                  <div style={{ fontSize: 14, fontWeight: 'bold', color: '#e1251b' }}>¥7,999.00</div>
                  <div style={{ fontSize: 12, color: '#52c41a', marginTop: 4 }}>运输中 (预计今日送达)</div>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>

      <MallFooter />
    </div>
  )
}
