import React, { useState, useMemo } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import {
  Breadcrumb,
  Tag,
  Pagination,
  Checkbox,
  Input,
  Button,
  Radio,
  Empty,
  message,
} from 'antd'
import {
  ShoppingCartOutlined,
  CustomerServiceOutlined,
  HeartOutlined,
  FilterOutlined,
  ArrowUpOutlined,
  ArrowDownOutlined,
} from '@ant-design/icons'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallHeader } from '../features/mall/components/MallHeader'
import { MallFooter } from '../features/mall/components/MallFooter'
import { useCartStore } from '../stores/cartStore'
import { z } from 'zod'

const searchSchema = z.object({
  q: z.string().optional(),
  brand: z.string().optional(),
  sort: z.enum(['default', 'sales', 'comments', 'price_asc', 'price_desc']).optional(),
  page: z.number().optional(),
})

export const Route = createFileRoute('/list')({
  validateSearch: (search) => searchSchema.parse(search),
  component: ProductListPage,
})

// 模拟完整商品数据库
const ALL_PRODUCTS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 移动联通电信5G手机',
    brand: 'Apple',
    price: 7999,
    originalPrice: 8999,
    sales: 120000,
    comments: 500000,
    rating: 98,
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&q=80',
    tags: ['自营', '满300减50', '24期免息'],
    sku: '原色钛金属 / 256GB',
    shopName: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 5,
    title: '华为 HUAWEI Mate 60 Pro 12GB+512GB 雅川青 卫星通话手机',
    brand: '华为',
    price: 6999,
    originalPrice: 7499,
    sales: 98000,
    comments: 320000,
    rating: 99,
    image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80',
    tags: ['自营', '卫星通信', '新品热卖'],
    sku: '雅川青 / 512GB',
    shopName: '华为京东自营官方旗舰店',
    inStock: true,
  },
  {
    id: 9,
    title: '小米 14 Pro 16GB+512GB 黑色 徕卡光学镜头 骁龙8Gen3手机',
    brand: '小米',
    price: 4999,
    originalPrice: 5499,
    sales: 85000,
    comments: 240000,
    rating: 97,
    image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&q=80',
    tags: ['自营', '徕卡影像', '急速闪充'],
    sku: '黑色 / 512GB',
    shopName: '小米京东自营旗舰店',
    inStock: true,
  },
  {
    id: 10,
    title: '荣耀 Magic6 Pro 16GB+512GB 绒黑色 鸿燕卫星通信',
    brand: '荣耀',
    price: 5699,
    originalPrice: 6199,
    sales: 43000,
    comments: 110000,
    rating: 98,
    image: 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=500&q=80',
    tags: ['自营', '单反级写真'],
    sku: '绒黑色 / 512GB',
    shopName: '荣耀京东自营旗舰店',
    inStock: true,
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 高解析度头戴式无线降噪耳机 铂金银',
    brand: '索尼',
    price: 1899,
    originalPrice: 2499,
    sales: 65000,
    comments: 150000,
    rating: 97,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
    tags: ['自营', '智能降噪', 'Hi-Res金标'],
    sku: '铂金银 / 官方标配',
    shopName: '索尼音频京东自营旗舰店',
    inStock: true,
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能户外运动手表 49mm 钛金属表壳',
    brand: 'Apple',
    price: 4999,
    originalPrice: 6299,
    sales: 38000,
    comments: 98000,
    rating: 98,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=500&q=80',
    tags: ['自营', '极限探险', '心电图血氧'],
    sku: '49mm钛金属 / 高山回环',
    shopName: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 6,
    title: 'Apple MacBook Pro 14英寸 M3 Max 芯片 36GB+1TB 深空黑',
    brand: 'Apple',
    price: 19999,
    originalPrice: 21999,
    sales: 15000,
    comments: 42000,
    rating: 99,
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&q=80',
    tags: ['自营', '专业生产力'],
    sku: 'M3 Max / 36GB / 1TB',
    shopName: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 8,
    title: 'Keychron Q1 Pro 无线客制化机械键盘 阳极氧化铝外壳',
    brand: 'Keychron',
    price: 899,
    originalPrice: 1099,
    sales: 29000,
    comments: 31000,
    rating: 96,
    image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80',
    tags: ['客制化', '热插拔'],
    sku: '茶轴 / 黑色',
    shopName: 'Keychron官方自营旗舰店',
    inStock: false,
  },
]

const BRANDS = ['全部', 'Apple', '华为', '小米', '荣耀', '索尼', 'Keychron']

function ProductListPage() {
  const searchParams = Route.useSearch()
  const navigate = useNavigate()
  const addItem = useCartStore((state) => state.addItem)

  const keyword = searchParams.q || ''
  const selectedBrand = searchParams.brand || '全部'
  const currentSort = searchParams.sort || 'default'

  // 快捷过滤条件
  const [onlyJdLogistics, setOnlyJdLogistics] = useState(true)
  const [onlyInStock, setOnlyInStock] = useState(false)
  const [priceRange, setPriceRange] = useState<{ min?: number; max?: number }>({})

  const handleBrandSelect = (brand: string) => {
    navigate({
      to: '/list',
      search: {
        ...searchParams,
        brand: brand === '全部' ? undefined : brand,
        page: 1,
      },
    })
  }

  const handleSortChange = (sort: 'default' | 'sales' | 'comments' | 'price_asc' | 'price_desc') => {
    navigate({
      to: '/list',
      search: {
        ...searchParams,
        sort,
        page: 1,
      },
    })
  }

  // 过滤商品
  const filteredProducts = useMemo(() => {
    return ALL_PRODUCTS.filter((item) => {
      // 关键字搜索
      if (keyword && !item.title.toLowerCase().includes(keyword.toLowerCase())) {
        return false
      }
      // 品牌过滤
      if (selectedBrand !== '全部' && item.brand !== selectedBrand) {
        return false
      }
      // 仅看有货
      if (onlyInStock && !item.inStock) {
        return false
      }
      // 价格区间
      if (priceRange.min !== undefined && item.price < priceRange.min) {
        return false
      }
      if (priceRange.max !== undefined && item.price > priceRange.max) {
        return false
      }
      return true
    }).sort((a, b) => {
      if (currentSort === 'sales') return b.sales - a.sales
      if (currentSort === 'comments') return b.comments - a.comments
      if (currentSort === 'price_asc') return a.price - b.price
      if (currentSort === 'price_desc') return b.price - a.price
      return 0
    })
  }, [keyword, selectedBrand, onlyInStock, priceRange, currentSort])

  const handleQuickAdd = (p: (typeof ALL_PRODUCTS)[0], e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    addItem({
      goodsId: p.id,
      title: p.title,
      price: p.price,
      quantity: 1,
      image: p.image,
      sku: p.sku,
      shopName: p.shopName,
    })
    message.success(`已将「${p.title}」加入购物车！`)
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        <MallShortcutNav />
        <MallHeader initialSearchKeyword={keyword} />

        <div className="max-w-7xl mx-auto px-4 py-4">
          {/* 面包屑导航与筛选结果统计 */}
          <div className="flex items-center justify-between pb-3 text-xs text-slate-500">
            <Breadcrumb
              items={[
                { title: <Link to="/">首页</Link> },
                { title: <Link to="/list">全部商品</Link> },
                { title: keyword ? `"${keyword}"` : selectedBrand },
              ]}
            />
            <div>
              共筛选出 <strong className="text-rose-600 font-bold">{filteredProducts.length}</strong> 件商品
            </div>
          </div>

          {/* 多维聚合筛选器面板 */}
          <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs divide-y divide-slate-100 p-4 mb-4 text-xs">
            {/* 品牌行 */}
            <div className="flex items-center py-2.5">
              <span className="w-20 font-bold text-slate-500 shrink-0">品 牌：</span>
              <div className="flex flex-wrap gap-2 flex-1">
                {BRANDS.map((b) => (
                  <button
                    key={b}
                    type="button"
                    onClick={() => handleBrandSelect(b)}
                    className={`px-3 py-1 rounded transition-colors cursor-pointer ${
                      (selectedBrand === b || (b === '全部' && !searchParams.brand))
                        ? 'bg-rose-600 text-white font-semibold'
                        : 'bg-slate-50 text-slate-700 hover:bg-rose-50 hover:text-rose-600'
                    }`}
                  >
                    {b}
                  </button>
                ))}
              </div>
            </div>

            {/* 价格区间 */}
            <div className="flex items-center py-2.5">
              <span className="w-20 font-bold text-slate-500 shrink-0">价 格：</span>
              <div className="flex items-center gap-3">
                <button
                  type="button"
                  onClick={() => setPriceRange({})}
                  className="px-2.5 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-700 cursor-pointer"
                >
                  全部
                </button>
                <button
                  type="button"
                  onClick={() => setPriceRange({ min: 0, max: 1999 })}
                  className="px-2.5 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-700 cursor-pointer"
                >
                  0-1999
                </button>
                <button
                  type="button"
                  onClick={() => setPriceRange({ min: 2000, max: 4999 })}
                  className="px-2.5 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-700 cursor-pointer"
                >
                  2000-4999
                </button>
                <button
                  type="button"
                  onClick={() => setPriceRange({ min: 5000, max: 9999 })}
                  className="px-2.5 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-700 cursor-pointer"
                >
                  5000-9999
                </button>
                <button
                  type="button"
                  onClick={() => setPriceRange({ min: 10000 })}
                  className="px-2.5 py-1 rounded bg-slate-50 hover:bg-rose-50 text-slate-700 cursor-pointer"
                >
                  10000以上
                </button>
              </div>
            </div>

            {/* 高级属性 */}
            <div className="flex items-center py-2.5">
              <span className="w-20 font-bold text-slate-500 shrink-0">特 色：</span>
              <div className="flex items-center gap-2">
                <Tag color="cyan">5G全网通</Tag>
                <Tag color="geekblue">快充闪充</Tag>
                <Tag color="purple">高端轻薄本</Tag>
                <Tag color="magenta">主动降噪</Tag>
              </div>
            </div>
          </div>

          {/* 排序与过滤工具条 */}
          <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-3 mb-6 flex flex-wrap items-center justify-between gap-4">
            {/* 排序按钮组 */}
            <div className="flex items-center gap-1">
              <button
                type="button"
                onClick={() => handleSortChange('default')}
                className={`px-3 py-1.5 text-xs rounded font-medium transition cursor-pointer ${
                  currentSort === 'default'
                    ? 'bg-rose-600 text-white'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                综合推荐
              </button>
              <button
                type="button"
                onClick={() => handleSortChange('sales')}
                className={`px-3 py-1.5 text-xs rounded font-medium transition cursor-pointer ${
                  currentSort === 'sales'
                    ? 'bg-rose-600 text-white'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                销量最高
              </button>
              <button
                type="button"
                onClick={() => handleSortChange('comments')}
                className={`px-3 py-1.5 text-xs rounded font-medium transition cursor-pointer ${
                  currentSort === 'comments'
                    ? 'bg-rose-600 text-white'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                评论数 ↓
              </button>
              <button
                type="button"
                onClick={() =>
                  handleSortChange(currentSort === 'price_asc' ? 'price_desc' : 'price_asc')
                }
                className={`px-3 py-1.5 text-xs rounded font-medium transition cursor-pointer flex items-center gap-1 ${
                  currentSort.startsWith('price')
                    ? 'bg-rose-600 text-white'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                <span>价格</span>
                {currentSort === 'price_asc' ? (
                  <ArrowUpOutlined className="text-[10px]" />
                ) : (
                  <ArrowDownOutlined className="text-[10px]" />
                )}
              </button>
            </div>

            {/* 勾选过滤 */}
            <div className="flex items-center gap-4 text-xs text-slate-600">
              <Checkbox
                checked={onlyJdLogistics}
                onChange={(e) => setOnlyJdLogistics(e.target.checked)}
              >
                京东物流 (自营直发)
              </Checkbox>
              <Checkbox
                checked={onlyInStock}
                onChange={(e) => setOnlyInStock(e.target.checked)}
              >
                仅看有货
              </Checkbox>
            </div>
          </div>

          {/* 商品网格列表 */}
          {filteredProducts.length === 0 ? (
            <div className="bg-white rounded-xl p-12 text-center border border-slate-200">
              <Empty description="没有找到符合条件的商品，尝试切换筛选条件看看吧" />
            </div>
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
              {filteredProducts.map((prod) => (
                <Link
                  key={prod.id}
                  to="/item/$id"
                  params={{ id: String(prod.id) }}
                  className="bg-white rounded-xl border border-slate-200/80 shadow-xs hover:shadow-lg transition-all p-3 flex flex-col justify-between group"
                >
                  <div>
                    {/* 商品主图 */}
                    <div className="aspect-square bg-slate-50 rounded-lg overflow-hidden relative mb-3">
                      <img
                        src={prod.image}
                        alt={prod.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                      />
                      <div className="absolute top-2 left-2 flex flex-col gap-1">
                        {prod.tags.map((t) => (
                          <span
                            key={t}
                            className="bg-rose-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm"
                          >
                            {t}
                          </span>
                        ))}
                      </div>
                      {!prod.inStock && (
                        <div className="absolute inset-0 bg-black/40 backdrop-blur-[1px] flex items-center justify-center">
                          <span className="bg-slate-900/90 text-white text-xs px-2.5 py-1 rounded">
                            暂时缺货
                          </span>
                        </div>
                      )}
                    </div>

                    {/* 价格与好评率 */}
                    <div className="flex items-baseline justify-between mb-1.5">
                      <div className="flex items-baseline gap-1.5">
                        <span className="text-lg font-black text-rose-600">
                          ¥{prod.price.toFixed(2)}
                        </span>
                        <span className="text-xs text-slate-400 line-through">
                          ¥{prod.originalPrice.toFixed(2)}
                        </span>
                      </div>
                      <span className="text-[11px] text-amber-600 font-medium">
                        {prod.rating}% 好评
                      </span>
                    </div>

                    {/* 商品标题 */}
                    <h3 className="text-xs text-slate-800 font-medium line-clamp-2 group-hover:text-rose-600 transition-colors h-8 mb-2">
                      {prod.title}
                    </h3>

                    {/* 评价与销量 */}
                    <div className="text-[11px] text-slate-400 flex items-center gap-3">
                      <span>{prod.comments > 10000 ? `${(prod.comments / 10000).toFixed(1)}万+` : prod.comments} 条评价</span>
                      <span>已售 {prod.sales > 10000 ? `${(prod.sales / 10000).toFixed(1)}万+` : prod.sales} 件</span>
                    </div>

                    {/* 店铺 */}
                    <div className="mt-2 text-[11px] text-slate-500 flex items-center justify-between border-t border-slate-50 pt-2">
                      <span className="truncate">{prod.shopName}</span>
                      <CustomerServiceOutlined className="text-slate-400 hover:text-rose-600" />
                    </div>
                  </div>

                  {/* 底部加购按钮 */}
                  <div className="mt-3 pt-2">
                    <Button
                      type="primary"
                      danger
                      ghost
                      icon={<ShoppingCartOutlined />}
                      block
                      disabled={!prod.inStock}
                      onClick={(e) => handleQuickAdd(prod, e)}
                      className="text-xs font-medium"
                    >
                      {prod.inStock ? '加入购物车' : '缺货登记'}
                    </Button>
                  </div>
                </Link>
              ))}
            </div>
          )}

          {/* 分页组件 */}
          <div className="flex justify-center my-8">
            <Pagination defaultCurrent={1} total={filteredProducts.length} pageSize={8} showQuickJumper />
          </div>
        </div>
      </div>

      <MallFooter />
    </div>
  )
}
