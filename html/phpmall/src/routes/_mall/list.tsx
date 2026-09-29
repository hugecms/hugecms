import React, { useState, useMemo } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../stores/cartStore'
import { z } from 'zod'
import styles from './list.module.css'

const searchSchema = z.object({
  q: z.string().optional(),
  brand: z.string().optional(),
  sort: z.enum(['default', 'sales', 'comments', 'price_asc', 'price_desc']).optional(),
  page: z.number().optional(),
})

export const Route = createFileRoute('/_mall/list')({
  validateSearch: (search) => searchSchema.parse(search),
  component: ExactProductListPage,
})

const ALL_PRODUCTS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 支持移动联通电信5G 双卡双待手机',
    brand: 'Apple',
    price: 7999,
    originalPrice: 8999,
    sales: 120000,
    comments: '50万+',
    rating: '98%',
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&q=80',
    tags: ['自营', '满300减50'],
    shop: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 5,
    title: '华为 HUAWEI Mate 60 Pro 12GB+512GB 雅川青 卫星通话手机',
    brand: '华为',
    price: 6999,
    originalPrice: 7499,
    sales: 98000,
    comments: '32万+',
    rating: '99%',
    image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&q=80',
    tags: ['自营', '卫星通信'],
    shop: '华为京东自营官方旗舰店',
    inStock: true,
  },
  {
    id: 9,
    title: '小米 14 Pro 16GB+512GB 黑色 徕卡光学镜头 骁龙8Gen3手机',
    brand: '小米',
    price: 4999,
    originalPrice: 5499,
    sales: 85000,
    comments: '24万+',
    rating: '97%',
    image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&q=80',
    tags: ['自营', '徕卡影像'],
    shop: '小米京东自营旗舰店',
    inStock: true,
  },
  {
    id: 10,
    title: '荣耀 Magic6 Pro 16GB+512GB 绒黑色 鸿燕卫星通信',
    brand: '荣耀',
    price: 5699,
    originalPrice: 6199,
    sales: 43000,
    comments: '11万+',
    rating: '98%',
    image: 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=500&q=80',
    tags: ['自营', '单反写真'],
    shop: '荣耀京东自营旗舰店',
    inStock: true,
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 高解析度头戴式无线降噪耳机 铂金银',
    brand: '索尼',
    price: 1899,
    originalPrice: 2499,
    sales: 65000,
    comments: '15万+',
    rating: '97%',
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
    tags: ['自营', '智能降噪'],
    shop: '索尼音频京东自营旗舰店',
    inStock: true,
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能户外运动手表 49mm 钛金属表壳',
    brand: 'Apple',
    price: 4999,
    originalPrice: 6299,
    sales: 38000,
    comments: '9.8万+',
    rating: '98%',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=500&q=80',
    tags: ['自营', '极限探险'],
    shop: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 6,
    title: 'Apple MacBook Pro 14英寸 M3 Max 芯片 36GB+1TB 深空黑',
    brand: 'Apple',
    price: 19999,
    originalPrice: 21999,
    sales: 15000,
    comments: '4.2万+',
    rating: '99%',
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&q=80',
    tags: ['自营', '生产力神器'],
    shop: 'Apple产品京东自营旗舰店',
    inStock: true,
  },
  {
    id: 8,
    title: 'Keychron Q1 Pro 无线客制化机械键盘 阳极氧化铝外壳',
    brand: 'Keychron',
    price: 899,
    originalPrice: 1099,
    sales: 29000,
    comments: '3.1万+',
    rating: '96%',
    image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80',
    tags: ['客制化', '热插拔'],
    shop: 'Keychron官方自营旗舰店',
    inStock: true,
  },
]

const BRANDS = ['全部', 'Apple', '华为', '小米', '荣耀', 'vivo', 'OPPO', '三星', '索尼']

function ExactProductListPage() {
  const searchParams = Route.useSearch()
  const navigate = useNavigate()
  const addItem = useCartStore((state) => state.addItem)

  const keyword = searchParams.q || '手机'
  const selectedBrand = searchParams.brand || '全部'
  const currentSort = searchParams.sort || 'default'

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

  const handleSortSelect = (sort: 'default' | 'sales' | 'comments' | 'price_asc' | 'price_desc') => {
    navigate({
      to: '/list',
      search: {
        ...searchParams,
        sort,
        page: 1,
      },
    })
  }

  const filteredProducts = useMemo(() => {
    return ALL_PRODUCTS.filter((item) => {
      if (
        keyword &&
        !item.title.toLowerCase().includes(keyword.toLowerCase()) &&
        !item.brand.toLowerCase().includes(keyword.toLowerCase())
      ) {
        return false
      }
      if (selectedBrand !== '全部' && item.brand !== selectedBrand) {
        return false
      }
      return true
    }).sort((a, b) => {
      if (currentSort === 'sales') return b.sales - a.sales
      if (currentSort === 'price_asc') return a.price - b.price
      if (currentSort === 'price_desc') return b.price - a.price
      return 0
    })
  }, [keyword, selectedBrand, currentSort])

  const handleAddToCart = (item: (typeof ALL_PRODUCTS)[0], e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: '正品官方标配',
      shopName: item.shop,
    })
    message.success(`已将「${item.title}」加入购物车！`)
  }

  return (
    <div style={{ backgroundColor: '#f4f4f4', paddingBottom: 40 }}>
      {/* 面包屑与结果统计 */}
      <div className={`w ${styles.listCrumb}`}>
        <Link to="/">全部结果</Link>
        <span className={styles.sep}>&gt;</span>
        <strong className={styles.current}>"{keyword}"</strong>
        <span className={styles.totalRes}>
          共筛选出 <strong>{filteredProducts.length * 1285}</strong> 件相关商品
        </span>
      </div>

      {/* 多维筛选器 (Selector Box) */}
      <section className={`w ${styles.selectorBox}`}>
        {/* 品牌行 */}
        <div className={styles.selectorRow}>
          <div className={styles.sKey}>品 牌：</div>
          <div className={styles.sValues}>
            {BRANDS.map((b) => (
              <button
                key={b}
                type="button"
                onClick={() => handleBrandSelect(b)}
                className={selectedBrand === b || (b === '全部' && !searchParams.brand) ? styles.active : ''}
              >
                {b}
              </button>
            ))}
          </div>
        </div>

        {/* 价格区间 */}
        <div className={styles.selectorRow}>
          <div className={styles.sKey}>价 格：</div>
          <div className={styles.sValues}>
            <button type="button" className={styles.active}>全部</button>
            <button type="button">0-1999</button>
            <button type="button">2000-3999</button>
            <button type="button">4000-5999</button>
            <button type="button">6000及以上</button>
            <div className={styles.priceInputRange}>
              <input type="text" placeholder="¥" /> - <input type="text" placeholder="¥" />
              <button
                type="button"
                className={styles.btnPriceOk}
                onClick={() => message.info('已应用价格区间筛选')}
              >
                确定
              </button>
            </div>
          </div>
        </div>

        {/* 运行内存 */}
        <div className={styles.selectorRow}>
          <div className={styles.sKey}>运行内存：</div>
          <div className={styles.sValues}>
            <button type="button" className={styles.active}>全部</button>
            <button type="button">8GB</button>
            <button type="button">12GB</button>
            <button type="button">16GB</button>
            <button type="button">24GB及以上</button>
          </div>
        </div>

        {/* 屏幕尺寸 */}
        <div className={styles.selectorRow}>
          <div className={styles.sKey}>屏幕尺寸：</div>
          <div className={styles.sValues}>
            <button type="button" className={styles.active}>全部</button>
            <button type="button">6.1英寸及以下 (小屏旗舰)</button>
            <button type="button">6.2 - 6.6英寸</button>
            <button type="button">6.7英寸及以上</button>
            <button type="button">大折叠双屏</button>
          </div>
        </div>
      </section>

      {/* 排序与过滤工具条 (Filter Bar) */}
      <section className={`w ${styles.filterBar}`}>
        <div className={styles.filterSortGroup}>
          <button
            type="button"
            className={`${styles.sortBtn} ${currentSort === 'default' ? styles.sortBtnActive : ''}`}
            onClick={() => handleSortSelect('default')}
          >
            综合推荐
          </button>
          <button
            type="button"
            className={`${styles.sortBtn} ${currentSort === 'sales' ? styles.sortBtnActive : ''}`}
            onClick={() => handleSortSelect('sales')}
          >
            销量最高
          </button>
          <button
            type="button"
            className={`${styles.sortBtn} ${currentSort === 'comments' ? styles.sortBtnActive : ''}`}
            onClick={() => handleSortSelect('comments')}
          >
            评论数 ↓
          </button>
          <button type="button" className={styles.sortBtn}>
            新品上市
          </button>
          <button
            type="button"
            className={`${styles.sortBtn} ${currentSort.startsWith('price') ? styles.sortBtnActive : ''}`}
            onClick={() => handleSortSelect(currentSort === 'price_asc' ? 'price_desc' : 'price_asc')}
          >
            价格 {currentSort === 'price_asc' ? '↑' : currentSort === 'price_desc' ? '↓' : '↕'}
          </button>
        </div>

        <div className={styles.filterExtraCheckboxes}>
          <label style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
            <input type="checkbox" defaultChecked />
            <span>京东物流 (自营直发)</span>
          </label>
          <label style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
            <input type="checkbox" defaultChecked />
            <span>仅看有货</span>
          </label>
          <label style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
            <input type="checkbox" />
            <span>货到付款</span>
          </label>
          <label style={{ display: 'flex', alignItems: 'center', gap: 4 }}>
            <input type="checkbox" />
            <span>促销满减</span>
          </label>
        </div>

        <div className={styles.filterPagerMini}>
          <span className={styles.pageNum}>
            <strong style={{ color: '#e1251b' }}>1</strong>/35
          </span>
          <button type="button" className={`${styles.btnPageStep} ${styles.btnPageStepDisabled}`}>
            &lt;
          </button>
          <button type="button" className={styles.btnPageStep}>
            &gt;
          </button>
        </div>
      </section>

      {/* 商品网格矩阵 (Goods Grid 5 列经典布局) */}
      <main className={`w ${styles.goodsGrid5cols}`}>
        {filteredProducts.map((prod) => (
          <div key={prod.id} className={styles.itemCard}>
            <Link to="/item/$id" params={{ id: String(prod.id) }} className={styles.thumb}>
              <img src={prod.image} alt={prod.title} />
            </Link>

            <div className={styles.itemBody}>
              <div className={styles.itemPrice}>
                <span className={styles.yen}>¥</span>
                <span className={styles.amount}>{prod.price}</span>
                <span className={styles.dec}>.00</span>
              </div>

              <Link
                to="/item/$id"
                params={{ id: String(prod.id) }}
                className={styles.itemTitle}
                title={prod.title}
              >
                <span style={{ backgroundColor: '#e1251b', color: '#fff', fontSize: 10, padding: '0 4px', borderRadius: 2, marginRight: 4 }}>
                  自营
                </span>
                {prod.title}
              </Link>

              <div className={styles.itemCommit}>
                已有 <span className={styles.cNum}>{prod.comments}</span> 人评价
                <span className={styles.rate}>{prod.rating}好评</span>
              </div>

              <div className={styles.itemTags}>
                {prod.tags.map((t) => (
                  <span key={t} className={styles.tBadge}>
                    {t}
                  </span>
                ))}
              </div>

              <div className={styles.itemShop} title={prod.shop}>
                {prod.shop}
              </div>

              <div className={styles.itemActionBtns}>
                <Link
                  to="/item/$id"
                  params={{ id: String(prod.id) }}
                  className={styles.btnViewDetail}
                >
                  查看详情
                </Link>
                <button
                  type="button"
                  className={styles.btnListAddCart}
                  onClick={(e) => handleAddToCart(prod, e)}
                >
                  🛒 购买
                </button>
              </div>
            </div>
          </div>
        ))}
      </main>

      {/* 底部翻页组件 */}
      <div className={`w ${styles.listPagination}`}>
        <button type="button" className={`${styles.pageBtn} ${styles.pageBtnDisabled}`}>&lt; 上一页</button>
        <button type="button" className={`${styles.pageBtn} ${styles.pageBtnActive}`}>1</button>
        <button type="button" className={styles.pageBtn}>2</button>
        <button type="button" className={styles.pageBtn}>3</button>
        <button type="button" className={styles.pageBtn}>4</button>
        <button type="button" className={styles.pageBtn}>5</button>
        <span style={{ padding: '0 4px', color: '#999' }}>...</span>
        <button type="button" className={styles.pageBtn}>35</button>
        <button type="button" className={styles.pageBtn}>下一页 &gt;</button>
      </div>
    </div>
  )
}

export default ExactProductListPage
