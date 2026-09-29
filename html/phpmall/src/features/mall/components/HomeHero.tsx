import React, { useState } from 'react'
import { Link } from '@tanstack/react-router'
import { Carousel, Avatar, Button, Tabs, Tag } from 'antd'
import {
  RightOutlined,
  UserOutlined,
  CrownOutlined,
  GiftOutlined,
  ThunderboltOutlined,
  ShopOutlined,
  CompassOutlined,
} from '@ant-design/icons'

// 14个核心大类与子分类数据
const CATEGORIES = [
  {
    id: 'digital',
    name: '手机 / 运营商 / 数码',
    sub: ['智能手机', '游戏手机', '单反微单', '耳机音响', '智能手环', '充电宝'],
    brands: ['Apple', '华为', '小米', '荣耀', 'vivo', 'OPPO', '索尼'],
  },
  {
    id: 'computer',
    name: '电脑 / 办公 / 文具',
    sub: ['轻薄本', '游戏本', '台式机', '显卡', '机械键盘', '显示器', '打印机'],
    brands: ['联想', '戴尔', '华硕', '惠普', 'ROG', '罗技'],
  },
  {
    id: 'appliance',
    name: '家用电器 / 电视 / 空调',
    sub: ['超薄电视', '新一级能效空调', '洗烘套装', '双开门冰箱', '空气炸锅'],
    brands: ['海尔', '美的', '格力', '索尼', 'TCL', '西门子'],
  },
  {
    id: 'clothing',
    name: '男装 / 女装 / 童装 / 内衣',
    sub: ['羽绒服', '卫衣', '牛仔裤', '针织衫', '运动冲锋衣', '童装套装'],
    brands: ['优衣库', '耐克', '阿迪达斯', '安踏', '波司登'],
  },
  {
    id: 'beauty',
    name: '美妆 / 个护清洁 / 宠物',
    sub: ['面霜乳液', '精华液', '口红彩妆', '香水', '猫粮狗粮', '洗护发'],
    brands: ['兰蔻', '雅诗兰黛', 'SK-II', '欧莱雅', '皇家猫粮'],
  },
  {
    id: 'food',
    name: '食品 / 生鲜 / 酒饮 / 滋补',
    sub: ['进口车厘子', '牛排生鲜', '飞天茅台', '精品咖啡', '零食礼包'],
    brands: ['茅台', '五粮液', '三只松鼠', '伊利', '蒙牛'],
  },
  {
    id: 'home',
    name: '家居 / 家具 / 家装 / 厨具',
    sub: ['实木大床', '人体工学椅', '乳胶床垫', '智能马桶', '不粘炒锅'],
    brands: ['全友', '顾家家居', '九牧', '双立人', '苏泊尔'],
  },
]

// 轮播 Banner 图
const BANNERS = [
  {
    id: 1,
    title: '年终爆款直降 • 领千元神券',
    desc: 'iPhone 16 Pro 现货至高立减 1000 元',
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=1200&q=80',
    link: '/item/1',
    tag: '大牌补贴',
  },
  {
    id: 2,
    title: '电竞极客装备季 • 满200减30',
    desc: '高端降噪耳机与 4K 高刷电竞屏盛宴',
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1200&q=80',
    link: '/item/2',
    tag: '数码超品日',
  },
  {
    id: 3,
    title: '智能穿戴健康伴侣 • 24期免息',
    desc: 'Apple Watch Ultra 极限运动探索手表',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=1200&q=80',
    link: '/item/3',
    tag: '新品上市',
  },
]

export const HomeHero: React.FC = () => {
  const [activeCategory, setActiveCategory] = useState<string | null>(null)

  const activeCategoryData = CATEGORIES.find((c) => c.id === activeCategory)

  return (
    <div className="bg-slate-100 py-3">
      <div className="max-w-7xl mx-auto px-4 flex gap-3 relative">
        {/* 左侧：分类垂直导航 */}
        <div
          className="w-52 bg-white rounded-lg shadow-sm border border-slate-200/80 p-2 shrink-0 z-20"
          onMouseLeave={() => setActiveCategory(null)}
        >
          <ul className="space-y-0.5">
            {CATEGORIES.map((cat) => (
              <li
                key={cat.id}
                onMouseEnter={() => setActiveCategory(cat.id)}
                className={`flex items-center justify-between px-3 py-2 text-xs rounded transition-colors cursor-pointer ${
                  activeCategory === cat.id
                    ? 'bg-rose-50 text-rose-600 font-semibold'
                    : 'text-slate-700 hover:bg-slate-50 hover:text-rose-600'
                }`}
              >
                <span className="truncate">{cat.name}</span>
                <RightOutlined className="text-[10px] text-slate-300" />
              </li>
            ))}
          </ul>

          {/* 浮动二级详情面板 */}
          {activeCategoryData && (
            <div
              className="absolute left-[224px] top-3 w-[680px] h-[374px] bg-white rounded-r-lg shadow-xl border border-slate-200 p-6 z-30 flex flex-col justify-between"
              onMouseEnter={() => setActiveCategory(activeCategoryData.id)}
              onMouseLeave={() => setActiveCategory(null)}
            >
              <div>
                <div className="flex items-center gap-2 pb-3 border-b border-slate-100">
                  <span className="text-base font-bold text-slate-800">
                    {activeCategoryData.name}
                  </span>
                  <Tag color="red">热卖专区</Tag>
                </div>

                <div className="mt-4">
                  <div className="text-xs font-semibold text-slate-400 mb-2">细分品类</div>
                  <div className="flex flex-wrap gap-2">
                    {activeCategoryData.sub.map((s) => (
                      <Link
                        key={s}
                        to="/list"
                        search={{ q: s }}
                        className="text-xs px-2.5 py-1 bg-slate-50 hover:bg-rose-50 text-slate-700 hover:text-rose-600 rounded border border-slate-100 transition-colors"
                      >
                        {s}
                      </Link>
                    ))}
                  </div>
                </div>

                <div className="mt-6">
                  <div className="text-xs font-semibold text-slate-400 mb-2">推荐热门品牌</div>
                  <div className="flex flex-wrap gap-3">
                    {activeCategoryData.brands.map((b) => (
                      <Link
                        key={b}
                        to="/list"
                        search={{ q: b }}
                        className="text-xs font-medium text-slate-600 hover:text-rose-600 hover:underline"
                      >
                        {b}
                      </Link>
                    ))}
                  </div>
                </div>
              </div>

              <div className="p-3 bg-gradient-to-r from-rose-50 to-orange-50 rounded-lg flex items-center justify-between">
                <span className="text-xs font-medium text-rose-800">
                  全品类补贴日：跨店满300减50，支持白条分期免息！
                </span>
                <Link to="/list" className="text-xs font-bold text-rose-600 hover:underline">
                  去抢购 &gt;
                </Link>
              </div>
            </div>
          )}
        </div>

        {/* 中间：大图轮播 */}
        <div className="flex-1 rounded-lg overflow-hidden shadow-sm relative h-[382px] bg-slate-900">
          <Carousel autoplay effect="fade" className="h-full">
            {BANNERS.map((banner) => (
              <div key={banner.id} className="relative h-[382px]">
                <img
                  src={banner.image}
                  alt={banner.title}
                  className="w-full h-full object-cover brightness-90"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-8">
                  <span className="inline-block px-2.5 py-0.5 bg-rose-600 text-white font-semibold text-xs rounded w-fit mb-2 shadow">
                    {banner.tag}
                  </span>
                  <h2 className="text-2xl font-black text-white drop-shadow-md">
                    {banner.title}
                  </h2>
                  <p className="text-sm text-slate-200 mt-1 mb-4">{banner.desc}</p>
                  <div>
                    <Link
                      to={banner.link as any}
                      className="inline-block px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs rounded-md shadow-lg transition-transform hover:scale-105"
                    >
                      立即查看商品
                    </Link>
                  </div>
                </div>
              </div>
            ))}
          </Carousel>
        </div>

        {/* 右侧：用户卡片 + 京东快报 + 便民服务 */}
        <div className="w-64 bg-white rounded-lg shadow-sm border border-slate-200/80 p-3 shrink-0 flex flex-col justify-between">
          {/* 用户信息卡 */}
          <div className="text-center pb-3 border-b border-slate-100">
            <Avatar size={48} icon={<UserOutlined />} className="bg-rose-500 shadow-sm" />
            <div className="text-sm font-bold text-slate-800 mt-1">Hi，欢迎光临京东！</div>
            <div className="flex justify-center gap-2 mt-2">
              <Link
                to="/user"
                className="text-xs px-3 py-1 bg-rose-600 text-white rounded font-medium hover:bg-rose-700 transition"
              >
                个人中心
              </Link>
              <Link
                to="/plus"
                className="text-xs px-3 py-1 bg-amber-500 text-white rounded font-medium hover:bg-amber-600 transition flex items-center gap-1"
              >
                <CrownOutlined /> PLUS会员
              </Link>
            </div>
            {/* 新人礼包 */}
            <div className="mt-3 p-2 bg-red-50/80 rounded border border-red-100 text-xs text-rose-700 flex items-center justify-between">
              <span className="flex items-center gap-1">
                <GiftOutlined className="text-rose-600" /> 新人专属
              </span>
              <span className="font-bold">领 ¥20 优惠券</span>
            </div>
          </div>

          {/* 京东快报 */}
          <div className="py-2">
            <div className="flex items-center justify-between text-xs font-bold text-slate-800 mb-2">
              <span className="flex items-center gap-1">
                <ThunderboltOutlined className="text-rose-600" /> 京东快报
              </span>
              <Link to="/list" className="text-[11px] text-slate-400 hover:text-rose-600">
                更多 &gt;
              </Link>
            </div>
            <ul className="space-y-1.5 text-xs">
              <li className="flex items-center gap-1.5 truncate">
                <Tag color="red" className="text-[10px] m-0 px-1 py-0">
                  热评
                </Tag>
                <Link to="/item/1" className="truncate text-slate-600 hover:text-rose-600">
                  iPhone 16 Pro 深度实测，钛金属机身究竟有多轻？
                </Link>
              </li>
              <li className="flex items-center gap-1.5 truncate">
                <Tag color="orange" className="text-[10px] m-0 px-1 py-0">
                  特惠
                </Tag>
                <Link to="/item/2" className="truncate text-slate-600 hover:text-rose-600">
                  索尼降噪大耳限时放价，音乐发烧友必入
                </Link>
              </li>
              <li className="flex items-center gap-1.5 truncate">
                <Tag color="blue" className="text-[10px] m-0 px-1 py-0">
                  公告
                </Tag>
                <span className="truncate text-slate-500">京东物流春节不打烊服务保障公告</span>
              </li>
            </ul>
          </div>

          {/* 便民生活服务快捷宫格 */}
          <div className="pt-2 border-t border-slate-100 grid grid-cols-4 gap-1 text-center text-xs">
            <Link to="/list" className="p-1 hover:bg-slate-50 rounded group">
              <div className="text-base group-hover:scale-110 transition-transform">📱</div>
              <div className="text-[11px] text-slate-600 mt-0.5">话费</div>
            </Link>
            <Link to="/list" className="p-1 hover:bg-slate-50 rounded group">
              <div className="text-base group-hover:scale-110 transition-transform">✈️</div>
              <div className="text-[11px] text-slate-600 mt-0.5">机票</div>
            </Link>
            <Link to="/seller" className="p-1 hover:bg-slate-50 rounded group">
              <div className="text-base group-hover:scale-110 transition-transform">💼</div>
              <div className="text-[11px] text-slate-600 mt-0.5">商家工作台</div>
            </Link>
            <Link to="/b2b" className="p-1 hover:bg-slate-50 rounded group">
              <div className="text-base group-hover:scale-110 transition-transform">🏢</div>
              <div className="text-[11px] text-slate-600 mt-0.5">企业采购</div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  )
}
