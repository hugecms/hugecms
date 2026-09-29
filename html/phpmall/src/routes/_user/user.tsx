import React from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Avatar, Tag } from 'antd'
import { UserOutlined, CrownFilled } from '@ant-design/icons'
import styles from './user.module.css'

export const Route = createFileRoute('/_user/user')({
  component: UserDashboardPage,
})

function UserDashboardPage() {
  return (
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
            <div className={styles.assetVal}>¥15,000</div>
            <div className={styles.assetLabel}>白条可用额度</div>
          </div>
          <div className={styles.assetItem}>
            <div className={styles.assetVal}>¥0.00</div>
            <div className={styles.assetLabel}>京东礼品卡</div>
          </div>
        </div>
      </div>

      {/* 快捷订单概览 */}
      <div className={styles.recentOrderCard}>
        <div className={styles.cardHeader}>
          <h4>近期订单跟踪</h4>
          <Link to="/user/order">查看全部订单 ›</Link>
        </div>

        <div className={styles.orderPreviewItem}>
          <div style={{ display: 'flex', gap: 16, alignItems: 'center' }}>
            <img
              src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=120&q=80"
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
  )
}

export default UserDashboardPage
