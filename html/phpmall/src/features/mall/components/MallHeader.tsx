import React, { useState } from 'react'
import { Link, useNavigate } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../../stores/cartStore'

interface MallHeaderProps {
  initialSearchKeyword?: string
  showCategoryDrawer?: boolean
}

export const MallHeader: React.FC<MallHeaderProps> = ({
  initialSearchKeyword = '',
}) => {
  const navigate = useNavigate()
  const [searchInput, setSearchInput] = useState(initialSearchKeyword)
  const cartItems = useCartStore((state) => state.items)
  const cartCount = cartItems.reduce((acc, it) => acc + it.quantity, 0)
  const cartTotalPrice = cartItems.reduce((acc, it) => acc + it.price * it.quantity, 0)

  const handleSearch = () => {
    navigate({
      to: '/list',
      search: searchInput.trim() ? { q: searchInput.trim() } : {},
    })
  }

  return (
    <header className="header">
      <div className="w header-main">
        {/* 品牌Logo */}
        <div className="header-logo">
          <Link to="/" className="logo-link">
            <div className="logo-box">
              <span className="logo-text">JD</span>
              <span className="logo-sub">京东</span>
            </div>
          </Link>
        </div>

        {/* 搜索主区域 */}
        <div className="header-search-wrap">
          <div className="search-bar">
            <input
              type="text"
              className="search-input"
              id="searchInput"
              placeholder="微单相机 爆款直降 跨店满减"
              autoComplete="off"
              value={searchInput}
              onChange={(e) => setSearchInput(e.target.value)}
              onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
            />
            <button
              type="button"
              className="search-photo-btn"
              title="拍照找同款"
              onClick={() => message.info('搜图功能已就绪')}
            >
              📷
            </button>
            <button
              type="button"
              className="search-submit-btn"
              id="searchBtn"
              onClick={handleSearch}
            >
              <span>搜索</span>
            </button>
          </div>
          {/* 搜索热词 */}
          <div className="hot-words">
            <Link to="/item/1" className="highlight">iPhone 16 Pro</Link>
            <Link to="/list" search={{ q: '洗烘套装' }}>洗烘套装</Link>
            <Link to="/list" search={{ q: '电竞显示器' }}>电竞显示器</Link>
            <Link to="/list" search={{ q: '羽绒服' }}>羽绒服特惠</Link>
            <Link to="/list" search={{ q: '黄金' }}>黄金饰品</Link>
            <Link to="/list" search={{ q: '茅台' }}>茅台特惠</Link>
            <Link to="/list" search={{ q: '机械键盘' }}>机械键盘</Link>
          </div>
        </div>

        {/* 购物车入口 */}
        <div className="header-cart" id="miniCart">
          <Link to="/cart" className="cart-trigger">
            <span className="cart-icon">🛒</span>
            <span className="cart-text">我的购物车</span>
            <span className="cart-count" id="cartCount">{cartCount}</span>
          </Link>
          {/* 购物车下拉悬浮层 */}
          <div className="cart-dropdown" id="cartDropdown">
            <div className="cart-list">
              {cartItems.length === 0 ? (
                <div style={{ padding: '20px', textAlign: 'center', color: '#999', fontSize: '12px' }}>
                  购物车空空如也，快去选购吧
                </div>
              ) : (
                cartItems.slice(0, 3).map((item) => (
                  <div key={item.id} className="cart-item">
                    <img src={item.image} alt={item.title} />
                    <div className="cart-item-info">
                      <Link to={`/item/${item.id}`} className="cart-item-title">
                        {item.title}
                      </Link>
                      <p className="cart-item-price">
                        ¥{item.price.toLocaleString()} × {item.quantity}
                      </p>
                    </div>
                  </div>
                ))
              )}
            </div>
            <div className="cart-footer">
              <div className="cart-total">
                共 <span className="highlight">{cartCount}</span> 件商品 金额合计：
                <span className="price-val">¥{cartTotalPrice.toLocaleString()}</span>
              </div>
              <Link to="/cart" className="cart-checkout-btn" style={{ display: 'block', textAlign: 'center' }}>
                去购物车结算
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* 主频道栏目导航条 */}
      <div className="header-channels">
        <div className="w channels-wrap">
          <div className="category-title">
            <span>全部商品分类</span>
          </div>
          <ul className="channel-nav-list">
            <li className="active"><Link to="/seckill">秒杀</Link></li>
            <li><Link to="/coupon">优惠券</Link></li>
            <li><Link to="/plus">PLUS会员</Link></li>
            <li><Link to="/list">品牌闪购</Link></li>
            <li><Link to="/list">京东超市</Link></li>
            <li><Link to="/list">京东生鲜</Link></li>
            <li><Link to="/list">京东国际</Link></li>
            <li><Link to="/list">京东电器</Link></li>
            <li><Link to="/list">拍卖频道</Link></li>
          </ul>
        </div>
      </div>
    </header>
  )
}
