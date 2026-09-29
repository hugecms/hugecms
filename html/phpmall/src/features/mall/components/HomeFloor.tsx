import React from 'react'
import { Link } from '@tanstack/react-router'
import { Button, Tag, message } from 'antd'
import { ShoppingCartOutlined, FireOutlined } from '@ant-design/icons'
import { useCartStore } from '../../../stores/cartStore'

interface FloorProduct {
  id: number
  title: string
  price: number
  marketPrice: number
  image: string
  tags: string[]
  commentCount: string
  sku: string
}

interface FloorSectionProps {
  floorNumber: string
  title: string
  icon: string
  subCategories: string[]
  bannerImg: string
  bannerTitle: string
  bannerSubtitle: string
  products: FloorProduct[]
}

export const HomeFloor: React.FC<FloorSectionProps> = ({
  floorNumber,
  title,
  icon,
  subCategories,
  bannerImg,
  bannerTitle,
  bannerSubtitle,
  products,
}) => {
  const addItem = useCartStore((state) => state.addItem)

  const handleAdd = (p: FloorProduct, e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    addItem({
      goodsId: p.id,
      title: p.title,
      price: p.price,
      quantity: 1,
      image: p.image,
      sku: p.sku,
      shopName: '京东自营官方旗舰店',
    })
    message.success(`已将「${p.title}」加入购物车！`)
  }

  return (
    <section className="max-w-7xl mx-auto px-4 my-8">
      {/* 楼层头部 */}
      <div className="flex items-center justify-between pb-3 border-b-2 border-slate-800 mb-4">
        <div className="flex items-center gap-3">
          <span className="w-7 h-7 rounded-full bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
            {floorNumber}
          </span>
          <h2 className="text-xl font-black text-slate-800 flex items-center gap-1.5">
            <span>{icon}</span>
            <span>{title}</span>
          </h2>
        </div>

        {/* 楼层分类标签 */}
        <div className="hidden sm:flex items-center gap-4 text-xs font-medium text-slate-600">
          {subCategories.map((sub) => (
            <Link
              key={sub}
              to="/list"
              search={{ q: sub }}
              className="hover:text-rose-600 transition-colors"
            >
              {sub}
            </Link>
          ))}
          <Link to="/list" className="text-rose-600 hover:underline">
            进入频道 &gt;
          </Link>
        </div>
      </div>

      {/* 楼层主体 */}
      <div className="grid grid-cols-1 lg:grid-cols-5 gap-4">
        {/* 左侧推荐大展位 */}
        <div className="lg:col-span-1 relative rounded-xl overflow-hidden shadow-sm group min-h-[300px]">
          <img
            src={bannerImg}
            alt={bannerTitle}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent p-5 flex flex-col justify-end text-white">
            <span className="text-[10px] tracking-widest font-bold uppercase bg-white/20 px-2 py-0.5 rounded w-fit mb-1 backdrop-blur-sm">
              TOP RECOMMEND
            </span>
            <h3 className="text-lg font-black leading-tight drop-shadow">{bannerTitle}</h3>
            <p className="text-xs text-slate-200 mt-1 mb-3">{bannerSubtitle}</p>
            <Link
              to="/list"
              className="inline-block text-center py-1.5 px-3 bg-rose-600 text-white text-xs font-medium rounded hover:bg-rose-700 transition"
            >
              探索更多爆款
            </Link>
          </div>
        </div>

        {/* 右侧商品网格 */}
        <div className="lg:col-span-4 grid grid-cols-2 md:grid-cols-4 gap-3">
          {products.map((p) => (
            <Link
              key={p.id}
              to="/item/$id"
              params={{ id: String(p.id) }}
              className="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow group flex flex-col justify-between"
            >
              <div>
                <div className="aspect-square bg-slate-50 rounded-lg overflow-hidden mb-2 relative">
                  <img
                    src={p.image}
                    alt={p.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                  />
                  <div className="absolute top-1.5 left-1.5 flex flex-col gap-1">
                    {p.tags.map((t) => (
                      <span
                        key={t}
                        className="bg-rose-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded"
                      >
                        {t}
                      </span>
                    ))}
                  </div>
                </div>

                <h4 className="text-xs text-slate-800 font-medium line-clamp-2 group-hover:text-rose-600 transition-colors h-8">
                  {p.title}
                </h4>

                <div className="mt-2 flex items-baseline gap-1.5">
                  <span className="text-sm font-black text-rose-600">¥{p.price.toFixed(2)}</span>
                  <span className="text-[11px] text-slate-400 line-through">
                    ¥{p.marketPrice.toFixed(2)}
                  </span>
                </div>
              </div>

              <div className="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                <span className="text-[11px] text-slate-400">{p.commentCount} 评价</span>
                <button
                  type="button"
                  onClick={(e) => handleAdd(p, e)}
                  title="加入购物车"
                  className="w-7 h-7 rounded-full bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white flex items-center justify-center transition-colors cursor-pointer"
                >
                  <ShoppingCartOutlined className="text-xs" />
                </button>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}
