import React from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Avatar, Tag, Button, Progress } from 'antd'
import {
  UserOutlined,
  CrownFilled,
  WalletOutlined,
  ShoppingOutlined,
  RightOutlined,
  ClockCircleOutlined,
} from '@ant-design/icons'
import { MallShortcutNav } from '../../features/mall/components/MallShortcutNav'
import { MallFooter } from '../../features/mall/components/MallFooter'
import { UserHeader } from '../../features/user/components/UserHeader'
import { UserSidebar } from '../../features/user/components/UserSidebar'

export const Route = createFileRoute('/user/')({
  component: UserDashboardPage,
})

function UserDashboardPage() {
  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        <MallShortcutNav />
        <UserHeader title="我的京东 - 个人中心" />

        <div className="max-w-7xl mx-auto px-4 py-6 flex gap-6">
          <UserSidebar />

          {/* 右侧核心面板 */}
          <main className="flex-1 space-y-6">
            {/* 用户全景名片卡 */}
            <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-6 flex flex-col md:flex-row items-center justify-between gap-6">
              <div className="flex items-center gap-4">
                <Avatar
                  size={64}
                  src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&q=80"
                  icon={<UserOutlined />}
                  className="border-2 border-rose-500 shadow-sm"
                />
                <div>
                  <div className="flex items-center gap-3">
                    <h3 className="text-lg font-bold text-slate-800">张三</h3>
                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-xs">
                      <CrownFilled className="text-amber-200" /> PLUS正式年卡会员
                    </span>
                  </div>
                  <div className="flex items-center gap-4 text-xs text-slate-500 mt-2">
                    <span>
                      小白信用: <strong className="text-emerald-600">102.5</strong> (极好)
                    </span>
                    <span>
                      京享值: <strong className="text-slate-800">12,850</strong>
                    </span>
                    <Tag color="success">实名已认证</Tag>
                  </div>
                </div>
              </div>

              {/* 资产四宫格 */}
              <div className="grid grid-cols-3 gap-4 text-center divide-x divide-slate-100 pl-4">
                <div className="px-3">
                  <div className="text-xl font-bold text-rose-600">5</div>
                  <div className="text-xs text-slate-400 mt-1">优惠券</div>
                </div>
                <div className="px-3">
                  <div className="text-xl font-bold text-slate-800">1,280</div>
                  <div className="text-xs text-slate-400 mt-1">京豆余额</div>
                </div>
                <div className="px-3">
                  <div className="text-xl font-bold text-slate-800">¥20,000</div>
                  <div className="text-xs text-slate-400 mt-1">白条可用额度</div>
                </div>
              </div>
            </div>

            {/* 交易履约状态四徽章 */}
            <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-5 grid grid-cols-4 gap-4 text-center">
              <Link to="/user/order" className="p-2 hover:bg-slate-50 rounded-lg transition group">
                <div className="text-2xl mb-1 group-hover:scale-110 transition-transform">💳</div>
                <div className="text-xs font-semibold text-slate-700">待付款 (0)</div>
              </Link>
              <Link to="/user/order" className="p-2 hover:bg-slate-50 rounded-lg transition group">
                <div className="text-2xl mb-1 group-hover:scale-110 transition-transform">🚚</div>
                <div className="text-xs font-semibold text-slate-700">
                  待收货 <span className="text-rose-600 font-bold">(1)</span>
                </div>
              </Link>
              <Link to="/user/order" className="p-2 hover:bg-slate-50 rounded-lg transition group">
                <div className="text-2xl mb-1 group-hover:scale-110 transition-transform">⭐</div>
                <div className="text-xs font-semibold text-slate-700">
                  待评价 <span className="text-rose-600 font-bold">(1)</span>
                </div>
              </Link>
              <Link to="/user/order" className="p-2 hover:bg-slate-50 rounded-lg transition group">
                <div className="text-2xl mb-1 group-hover:scale-110 transition-transform">🔄</div>
                <div className="text-xs font-semibold text-slate-700">返修/售后 (0)</div>
              </Link>
            </div>

            {/* 近期订单速览 */}
            <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-6">
              <div className="flex items-center justify-between pb-4 border-b border-slate-100">
                <h4 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                  <ShoppingOutlined className="text-rose-600" /> 最近订单
                </h4>
                <Link to="/user/order" className="text-xs text-rose-600 hover:underline">
                  查看全部订单 &gt;
                </Link>
              </div>

              <div className="divide-y divide-slate-100 mt-2">
                <div className="py-4 flex items-center justify-between gap-4">
                  <div className="flex items-center gap-4">
                    <img
                      src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=100&q=80"
                      alt="iPhone"
                      className="w-16 h-16 rounded-lg object-cover border border-slate-200"
                    />
                    <div>
                      <h5 className="text-xs font-bold text-slate-800">
                        Apple iPhone 16 Pro 256GB 原色钛金属
                      </h5>
                      <p className="text-xs text-slate-400 mt-1">订单号：JD2026092800101</p>
                      <span className="text-xs text-slate-500">2026-09-28 14:32:05</span>
                    </div>
                  </div>

                  <div className="text-right">
                    <span className="text-sm font-bold text-slate-800 block">¥7,999.00</span>
                    <Tag color="processing" className="mt-1">
                      京东快递 运输中
                    </Tag>
                  </div>

                  <div>
                    <Link to="/user/order">
                      <Button size="small" type="primary" danger>
                        查看物流
                      </Button>
                    </Link>
                  </div>
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
