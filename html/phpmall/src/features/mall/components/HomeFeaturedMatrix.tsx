import React from 'react'
import { Link } from '@tanstack/react-router'
import { message } from 'antd'

export const HomeFeaturedMatrix: React.FC = () => {
  const handleGetCoupon = (name: string) => {
    message.success(`恭喜！已成功领取「${name}」！`)
  }

  return (
    <section className="feature-matrix w" id="sec-features">
      {/* 每日特价卡片 */}
      <div className="matrix-card">
        <div className="card-head">
          <h3 className="head-title">每日特价</h3>
          <span className="head-sub">大牌直降 超值抢</span>
        </div>
        <div className="card-body-double">
          <Link to="/item/1" className="sub-item" style={{ textDecoration: 'none', color: 'inherit' }}>
            <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=180&q=80" alt="纯棉卫衣" />
            <p className="name">加绒保暖卫衣</p>
            <span className="price">¥79.00</span>
          </Link>
          <Link to="/item/2" className="sub-item" style={{ textDecoration: 'none', color: 'inherit' }}>
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=180&q=80" alt="跑步鞋" />
            <p className="name">轻量缓震跑鞋</p>
            <span className="price">¥199.00</span>
          </Link>
        </div>
      </div>

      {/* 京东排行榜 */}
      <div className="matrix-card">
        <div className="card-head">
          <h3 className="head-title">热卖排行榜</h3>
          <span className="head-sub">跟榜买 不踩坑</span>
        </div>
        <div className="rank-list">
          <div className="rank-item">
            <span className="rank-num top1">1</span>
            <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=100&q=80" alt="智能手机" />
            <div className="rank-info">
              <p className="name">旗舰5G智能手机 512GB</p>
              <p className="desc">热销 5.2 万+ 件</p>
            </div>
          </div>
          <div className="rank-item">
            <span className="rank-num top2">2</span>
            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=100&q=80" alt="运动腕表" />
            <div className="rank-info">
              <p className="name">蓝宝石镜面智能运动腕表</p>
              <p className="desc">好评率 99%</p>
            </div>
          </div>
          <div className="rank-item">
            <span className="rank-num top3">3</span>
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&q=80" alt="头戴耳机" />
            <div className="rank-info">
              <p className="name">降噪大师 无线头戴耳机</p>
              <p className="desc">近30天回购榜第1名</p>
            </div>
          </div>
        </div>
      </div>

      {/* 新品首发 */}
      <div className="matrix-card">
        <div className="card-head">
          <h3 className="head-title">新品首发</h3>
          <span className="head-sub">前沿好物抢先体验</span>
        </div>
        <div className="card-body-double">
          <Link to="/item/1" className="sub-item" style={{ textDecoration: 'none', color: 'inherit' }}>
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=180&q=80" alt="超薄轻薄本" />
            <p className="name">AI轻薄本 首发立减</p>
            <span className="price">¥5,499</span>
          </Link>
          <Link to="/item/2" className="sub-item" style={{ textDecoration: 'none', color: 'inherit' }}>
            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=180&q=80" alt="折叠屏手机" />
            <p className="name">双旋水滴铰链折叠屏</p>
            <span className="price">¥7,999</span>
          </Link>
        </div>
      </div>

      {/* 领券中心 */}
      <div className="matrix-card coupon-card">
        <div className="card-head">
          <h3 className="head-title">领券中心</h3>
          <span className="head-sub">大额神券天天领</span>
        </div>
        <div className="coupon-list">
          <div className="coupon-item">
            <div className="c-left">
              <span className="c-val"><small>¥</small>100</span>
              <span className="c-cond">满1000可用</span>
            </div>
            <div className="c-right">
              <span className="c-desc">全品类家电券</span>
              <button
                type="button"
                className="c-btn"
                onClick={() => handleGetCoupon('全品类家电券 满1000减100')}
              >
                立即领取
              </button>
            </div>
          </div>
          <div className="coupon-item">
            <div className="c-left">
              <span className="c-val"><small>¥</small>30</span>
              <span className="c-cond">满200可用</span>
            </div>
            <div className="c-right">
              <span className="c-desc">超市百货通用券</span>
              <button
                type="button"
                className="c-btn"
                onClick={() => handleGetCoupon('超市百货通用券 满200减30')}
              >
                立即领取
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
