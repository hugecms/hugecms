import React, { useState } from 'react'
import { Link } from '@tanstack/react-router'

export const MallShortcutNav: React.FC = () => {
  const [currentCity] = useState('北京')

  return (
    <div className="shortcut-nav">
      <div className="w container-flex">
        <div className="nav-location">
          <span className="location-icon">📍</span>
          <span className="current-city">{currentCity}</span>
        </div>
        <ul className="nav-quick-links">
          <li className="login-item">
            <Link to="/login" className="highlight">你好，请登录</Link>
            <Link to="/register" className="register-btn">免费注册</Link>
          </li>
          <li className="spacer"></li>
          <li><Link to="/user/order">我的订单</Link></li>
          <li className="spacer"></li>
          <li className="dropdown">
            <Link to="/user">我的京东 <span className="arrow-down">▾</span></Link>
            <div className="dropdown-layer">
              <Link to="/user/order">待处理订单</Link>
              <Link to="/user/order">返修退换货</Link>
              <a href="javascript:;">降价商品</a>
              <a href="javascript:;">我的关注</a>
              <a href="javascript:;">京东通信</a>
            </div>
          </li>
          <li className="spacer"></li>
          <li><Link to="/plus">京东会员</Link></li>
          <li className="spacer"></li>
          <li className="dropdown">
            <Link to="/b2b">企业采购 <span className="arrow-down">▾</span></Link>
            <div className="dropdown-layer">
              <Link to="/b2b">企业购</Link>
              <Link to="/b2b">商用场景馆</Link>
              <Link to="/b2b">工业品与员工福利</Link>
            </div>
          </li>
          <li className="spacer"></li>
          <li className="dropdown">
            <a href="javascript:;">商家服务 <span className="arrow-down">▾</span></a>
            <div className="dropdown-layer">
              <Link to="/merchant/settle">商家入驻申请</Link>
              <Link to="/seller">京麦商家工作台</Link>
              <Link to="/shop">品牌旗舰店展示</Link>
              <Link to="/admin">平台运营总控</Link>
            </div>
          </li>
          <li className="spacer"></li>
          <li className="dropdown">
            <Link to="/chat">客户服务 <span className="arrow-down">▾</span></Link>
            <div className="dropdown-layer">
              <Link to="/chat">在线客服</Link>
              <Link to="/user/order">返修退换货</Link>
              <Link to="/user">收货地址</Link>
              <Link to="/user/order">订单跟踪</Link>
            </div>
          </li>
          <li className="spacer"></li>
          <li><Link to="/list">网站导航</Link></li>
          <li className="spacer"></li>
          <li className="mobile-jd">
            <a href="javascript:;">手机京东</a>
          </li>
        </ul>
      </div>
    </div>
  )
}
