import React, { useState } from 'react'
import { Link } from '@tanstack/react-router'

const FLOOR_TABS = ['热门推荐', '品质家电', '电脑外设', '智能影音', '游戏电竞']

const FLOOR_GOODS = [
  {
    id: 201,
    title: '11英寸 2.8K护眼高刷平板电脑 256GB',
    price: '2,499.00',
    image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=260&q=80',
  },
  {
    id: 202,
    title: '14核标压轻薄本 全金属机身 超长续航',
    price: '4,699.00',
    image: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=260&q=80',
  },
  {
    id: 203,
    title: '轻量化无线双模电竞鼠标 30000DPI传感器',
    price: '399.00',
    image: 'https://images.unsplash.com/photo-1510519138161-58474dfab9c7?w=260&q=80',
  },
  {
    id: 204,
    title: '重低音户外防水便携蓝牙音箱 IPX7级',
    price: '299.00',
    image: 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=260&q=80',
  },
]

export const HomeFloor: React.FC = () => {
  const [activeTab, setActiveTab] = useState(0)

  return (
    <section className="floor-section w" id="sec-digital">
      <div className="floor-header">
        <div className="floor-title-wrap">
          <span className="floor-flag">3C</span>
          <h3 className="floor-title">数码家电馆</h3>
        </div>
        <ul className="floor-tabs">
          {FLOOR_TABS.map((tab, idx) => (
            <li
              key={tab}
              className={idx === activeTab ? 'active' : ''}
              onClick={() => setActiveTab(idx)}
            >
              <a href="javascript:;">{tab}</a>
            </li>
          ))}
        </ul>
      </div>
      <div className="floor-body">
        {/* 楼层特色左侧大展板 */}
        <div className="floor-banner" style={{ background: 'linear-gradient(180deg, #304352 0%, #1d2731 100%)' }}>
          <div className="banner-text">
            <h4>智能数码巅峰月</h4>
            <p>国家补贴最高2000元</p>
          </div>
          <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?w=300&q=80" alt="数码电子" className="banner-img" />
          <ul className="banner-tags">
            <li>以旧换新</li>
            <li>保修无忧</li>
            <li>分期免息</li>
          </ul>
        </div>

        {/* 楼层商品矩阵 (4/8宫格) */}
        <div className="floor-goods-grid">
          {FLOOR_GOODS.map((item) => (
            <Link
              key={item.id}
              to="/item/$id"
              params={{ id: String(item.id) }}
              className="f-goods-card"
              style={{ textDecoration: 'none', color: 'inherit' }}
            >
              <img src={item.image} alt={item.title} />
              <p className="title">{item.title}</p>
              <div className="meta">
                <span className="price">¥{item.price}</span>
                <span className="badge-zy">自营</span>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}
