import React from 'react'
import { Link, useRouterState } from '@tanstack/react-router'
import {
  UserOutlined,
  ShoppingOutlined,
  WalletOutlined,
  HeartOutlined,
  CustomerServiceOutlined,
  SettingOutlined,
} from '@ant-design/icons'

const USER_MENUS = [
  {
    group: '订单中心',
    items: [
      { key: '/user/order', label: '我的订单', badge: '2', to: '/user/order' },
      { key: '/user/comment', label: '评价晒单', badge: '1', to: '/user/order' },
      { key: '/user/favorite', label: '常购商品', to: '/user' },
    ],
  },
  {
    group: '我的资产',
    items: [
      { key: '/user/coupon', label: '优惠券', badge: '5', to: '/user' },
      { key: '/user/beans', label: '京豆余额 (1,280)', to: '/user' },
      { key: '/user/baitiao', label: '京东白条', to: '/user' },
    ],
  },
  {
    group: '关注中心',
    items: [
      { key: '/user/fav_goods', label: '关注商品 (12)', to: '/user' },
      { key: '/user/fav_shops', label: '关注店铺 (5)', to: '/user' },
      { key: '/user/history', label: '浏览足迹', to: '/user' },
    ],
  },
  {
    group: '客户服务',
    items: [
      { key: '/user/refund', label: '返修退换货', to: '/user' },
      { key: '/chat', label: '智能在线客服', to: '/chat' },
    ],
  },
  {
    group: '账号设置',
    items: [
      { key: '/user/profile', label: '个人信息', to: '/user' },
      { key: '/user/address', label: '收货地址管理', to: '/user' },
    ],
  },
]

export const UserSidebar: React.FC = () => {
  const routerState = useRouterState()
  const currentPath = routerState.location.pathname

  return (
    <aside className="w-52 shrink-0 bg-white rounded-xl border border-slate-200/80 shadow-xs p-4">
      {/* 首页快捷 */}
      <div className="pb-3 border-b border-slate-100">
        <Link
          to="/user"
          className={`flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-bold transition ${
            currentPath === '/user'
              ? 'bg-rose-50 text-rose-600'
              : 'text-slate-700 hover:bg-slate-50 hover:text-rose-600'
          }`}
        >
          <UserOutlined />
          <span>我的京东首页</span>
        </Link>
      </div>

      <div className="mt-4 space-y-5">
        {USER_MENUS.map((menu) => (
          <div key={menu.group}>
            <div className="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">
              {menu.group}
            </div>
            <ul className="space-y-0.5">
              {menu.items.map((item) => {
                const isActive = currentPath === item.to && currentPath !== '/user'
                return (
                  <li key={item.key}>
                    <Link
                      to={item.to as any}
                      className={`flex items-center justify-between px-3 py-1.5 rounded-lg text-xs transition ${
                        isActive
                          ? 'bg-rose-50 text-rose-600 font-semibold'
                          : 'text-slate-600 hover:bg-slate-50 hover:text-rose-600'
                      }`}
                    >
                      <span>{item.label}</span>
                      {item.badge && (
                        <span className="text-[10px] px-1.5 py-0.2 bg-rose-100 text-rose-600 font-bold rounded-full">
                          {item.badge}
                        </span>
                      )}
                    </Link>
                  </li>
                )
              })}
            </ul>
          </div>
        ))}
      </div>
    </aside>
  )
}
