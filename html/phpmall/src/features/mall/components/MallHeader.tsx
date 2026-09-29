import React, { useState } from 'react'
import { Link, useNavigate } from '@tanstack/react-router'
import { Input, Badge, Dropdown, Button, Empty } from 'antd'
import {
  ShoppingCartOutlined,
  SearchOutlined,
  CameraOutlined,
  RightOutlined,
  FireOutlined,
} from '@ant-design/icons'
import { useCartStore } from '../../../stores/cartStore'

interface MallHeaderProps {
  initialSearchKeyword?: string
  showMegaMenu?: boolean
}

export const MallHeader: React.FC<MallHeaderProps> = ({
  initialSearchKeyword = '',
  showMegaMenu = false,
}) => {
  const navigate = useNavigate()
  const [keyword, setKeyword] = useState(initialSearchKeyword)
  const items = useCartStore((state) => state.items)
  const totalCount = items.reduce((sum, item) => sum + item.quantity, 0)
  const totalPrice = items.reduce((sum, item) => sum + item.price * item.quantity, 0)

  const handleSearch = (val?: string) => {
    const q = (val !== undefined ? val : keyword).trim()
    navigate({
      to: '/list',
      search: q ? { q } : {},
    })
  }

  // 购物车悬浮预览
  const cartDropdownContent = (
    <div className="w-80 bg-white rounded-lg shadow-xl border border-slate-100 p-3">
      <div className="font-medium text-slate-800 text-sm pb-2 border-b border-slate-100 flex justify-between items-center">
        <span>最新加入的商品</span>
        <span className="text-xs text-slate-400">共 {totalCount} 件</span>
      </div>

      {items.length === 0 ? (
        <div className="py-6 text-center">
          <Empty image={Empty.PRESENTED_IMAGE_SIMPLE} description="购物车空空如也" />
        </div>
      ) : (
        <>
          <div className="max-h-60 overflow-y-auto divide-y divide-slate-50 my-2">
            {items.slice(0, 4).map((item) => (
              <div key={item.id} className="py-2 flex items-center gap-2">
                <img
                  src={item.image}
                  alt={item.title}
                  className="w-10 h-10 object-cover rounded border border-slate-200 shrink-0"
                />
                <div className="flex-1 min-w-0">
                  <p className="text-xs text-slate-700 truncate">{item.title}</p>
                  <p className="text-xs text-slate-400 truncate">{item.sku}</p>
                </div>
                <div className="text-right shrink-0">
                  <span className="text-xs font-semibold text-rose-600">
                    ¥{item.price.toFixed(2)}
                  </span>
                  <span className="text-xs text-slate-400 block">x{item.quantity}</span>
                </div>
              </div>
            ))}
          </div>

          <div className="pt-2 border-t border-slate-100 flex items-center justify-between">
            <div>
              <span className="text-xs text-slate-500">共计: </span>
              <span className="text-sm font-bold text-rose-600">¥{totalPrice.toFixed(2)}</span>
            </div>
            <Link
              to="/cart"
              className="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded transition shadow-sm"
            >
              去购物车结算
            </Link>
          </div>
        </>
      )}
    </div>
  )

  const hotKeywords = [
    { text: 'iPhone 16 Pro', highlight: true },
    { text: '降噪耳机', highlight: false },
    { text: '机械键盘', highlight: false },
    { text: '微单相机', highlight: false },
    { text: '洗烘套装', highlight: false },
    { text: '茅台特惠', highlight: false },
    { text: '智能手表', highlight: false },
  ]

  const channels = [
    { title: '秒杀', link: '/#seckill', icon: '⚡' },
    { title: '优惠券', link: '/list', icon: '🎫' },
    { title: 'PLUS会员', link: '/plus', icon: '👑' },
    { title: '品牌特卖', link: '/list', icon: '🏷️' },
    { title: '企业采购', link: '/b2b', icon: '🏢' },
    { title: '京麦工作台', link: '/seller', icon: '💼' },
  ]

  return (
    <div className="bg-white border-b border-slate-200">
      {/* 搜索与品牌主体 */}
      <div className="max-w-7xl mx-auto px-4 pt-5 pb-4 flex items-center justify-between gap-8">
        {/* 京东经典红 Logo */}
        <Link to="/" className="flex items-center gap-3 shrink-0 group">
          <div className="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-md shadow-red-200 group-hover:scale-105 transition-transform">
            <span className="text-white font-black text-2xl tracking-tighter">JD</span>
          </div>
          <div>
            <div className="text-2xl font-black tracking-tight text-red-600 leading-none">
              京东商城
            </div>
            <div className="text-[11px] text-slate-400 tracking-wider font-medium mt-1">
              JD.COM • 多快好省
            </div>
          </div>
        </Link>

        {/* 居中搜索区 */}
        <div className="flex-1 max-w-2xl">
          <div className="flex items-center">
            <div className="relative flex-1">
              <Input
                size="large"
                value={keyword}
                onChange={(e) => setKeyword(e.target.value)}
                onPressEnter={() => handleSearch()}
                placeholder="搜索 商品 / 品牌 / 品类，如 iPhone 16 Pro、无线耳机"
                prefix={<SearchOutlined className="text-slate-400 mr-1" />}
                suffix={
                  <CameraOutlined
                    title="拍照/搜图"
                    className="text-slate-400 hover:text-rose-600 cursor-pointer text-base transition-colors"
                  />
                }
                className="rounded-r-none border-2 border-r-0 border-rose-600 hover:border-rose-600 focus:border-rose-600"
              />
            </div>
            <button
              onClick={() => handleSearch()}
              className="bg-rose-600 hover:bg-rose-700 text-white font-medium px-7 h-10 rounded-r-lg transition-colors flex items-center justify-center text-sm shadow-sm cursor-pointer"
            >
              搜索
            </button>
          </div>

          {/* 热门搜索标签 */}
          <div className="flex items-center gap-3 mt-2 text-xs overflow-hidden h-5">
            <span className="text-slate-400 shrink-0">热搜:</span>
            {hotKeywords.map((hk) => (
              <button
                key={hk.text}
                type="button"
                onClick={() => {
                  setKeyword(hk.text)
                  handleSearch(hk.text)
                }}
                className={`truncate hover:underline cursor-pointer ${
                  hk.highlight ? 'text-rose-600 font-semibold' : 'text-slate-500 hover:text-slate-800'
                }`}
              >
                {hk.text}
              </button>
            ))}
          </div>
        </div>

        {/* 右侧购物车按钮与悬浮层 */}
        <div className="shrink-0">
          <Dropdown popupRender={() => cartDropdownContent} placement="bottomRight" trigger={['hover']}>
            <Link
              to="/cart"
              className="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg hover:border-rose-500 hover:bg-rose-50/50 transition-all text-slate-700"
            >
              <Badge count={totalCount} size="small" offset={[2, -2]}>
                <ShoppingCartOutlined className="text-xl text-rose-600" />
              </Badge>
              <span className="text-sm font-medium">我的购物车</span>
            </Link>
          </Dropdown>
        </div>
      </div>

      {/* 底部全部分类栏目与核心频道 */}
      <div className="border-t border-slate-100 bg-white">
        <div className="max-w-7xl mx-auto px-4 flex items-center">
          {/* 全部商品分类触发器 */}
          <div
            className={`w-52 py-3 px-4 flex items-center justify-between font-bold text-sm text-white bg-rose-600 cursor-pointer select-none ${
              showMegaMenu ? 'rounded-t-lg' : 'rounded-none'
            }`}
          >
            <span>全部商品分类</span>
            <span className="text-xs opacity-90">☰</span>
          </div>

          {/* 频道横向导航 */}
          <nav className="flex items-center gap-6 ml-6 py-2.5 text-sm font-medium text-slate-700">
            {channels.map((c) => (
              <Link
                key={c.title}
                to={c.link as any}
                className="hover:text-rose-600 transition-colors flex items-center gap-1"
              >
                <span className="text-xs">{c.icon}</span>
                <span>{c.title}</span>
              </Link>
            ))}
          </nav>
        </div>
      </div>
    </div>
  )
}
