import React from 'react'
import { Link } from '@tanstack/react-router'
import { ShoppingCartOutlined } from '@ant-design/icons'
import { useCartStore } from '../../../stores/cartStore'
import { message } from 'antd'
import type { GoodsItem } from '../../../types/goods'

export interface GoodsCardProps {
  goods: GoodsItem
  className?: string
  showAddToCart?: boolean
}

export const GoodsCard: React.FC<GoodsCardProps> = ({
  goods,
  className = '',
  showAddToCart = true,
}) => {
  const addItem = useCartStore((s) => s.addItem)

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    addItem({
      id: Number(goods.id),
      name: goods.title,
      price: goods.price,
      image: goods.image,
      shop: goods.shop,
      tag: goods.tags?.[0],
    })
    message.success('已成功加入购物车')
  }

  return (
    <div
      className={`group relative flex flex-col rounded-lg bg-white p-3 border border-transparent hover:border-[#e1251b] transition shadow-xs hover:shadow-md ${className}`}
    >
      <Link to="/item/$id" params={{ id: String(goods.id) }} className="no-underline block">
        <div className="relative aspect-square w-full overflow-hidden rounded bg-[#f7f7f7] mb-2.5">
          <img
            src={goods.image}
            alt={goods.title}
            className="h-full w-full object-cover object-center group-hover:scale-105 transition duration-300"
            loading="lazy"
          />
          {goods.tags && goods.tags.length > 0 && (
            <div className="absolute left-2 top-2 flex flex-wrap gap-1">
              <span className="rounded bg-[#e1251b] px-1.5 py-0.5 text-[10px] font-bold text-white leading-none">
                {goods.tags[0]}
              </span>
            </div>
          )}
        </div>

        <div className="flex items-baseline gap-1 text-[#e1251b]">
          <span className="text-xs font-bold">¥</span>
          <span className="text-lg font-black tracking-tight">{goods.price}</span>
          {goods.originalPrice && (
            <span className="text-xs text-gray-400 line-through ml-1">
              ¥{goods.originalPrice}
            </span>
          )}
        </div>

        <h4 className="mt-1 line-clamp-2 text-xs text-gray-700 leading-snug group-hover:text-[#e1251b] transition">
          {goods.title}
        </h4>
      </Link>

      <div className="mt-2 flex items-center justify-between text-[11px] text-gray-400">
        <span>{goods.comments} 条评价</span>
        <span>好评率 {goods.rating}</span>
      </div>

      <div className="mt-1 flex items-center justify-between">
        <span className="truncate text-[11px] text-gray-500 max-w-[130px]">{goods.shop}</span>
        {showAddToCart && (
          <button
            type="button"
            onClick={handleAddToCart}
            className="flex h-6 w-6 items-center justify-center rounded-full bg-rose-50 text-[#e1251b] hover:bg-[#e1251b] hover:text-white transition cursor-pointer border-none"
            title="加入购物车"
          >
            <ShoppingCartOutlined className="text-xs" />
          </button>
        )}
      </div>
    </div>
  )
}

export default GoodsCard
