import React from 'react'
import { Link } from '@tanstack/react-router'
import { Checkbox, InputNumber, Button, Empty } from 'antd'
import { DeleteOutlined } from '@ant-design/icons'
import { useCartStore, type CartItem } from '../../../stores/cartStore'

export interface CartListProps {
  onItemClick?: (item: CartItem) => void
}

export const CartList: React.FC<CartListProps> = () => {
  const { items, toggleSelect, updateQuantity, removeItem } = useCartStore()

  if (items.length === 0) {
    return (
      <div className="bg-white rounded-lg p-12 text-center">
        <Empty description="购物车还是空的，快去挑选心仪的商品吧！">
          <Link to="/list">
            <Button type="primary" danger>
              去选购
            </Button>
          </Link>
        </Empty>
      </div>
    )
  }

  return (
    <div className="bg-white rounded-lg border border-slate-200 divide-y divide-slate-100">
      {items.map((item) => (
        <div
          key={item.id}
          className={`flex items-center gap-4 p-4 transition ${
            item.selected ? 'bg-amber-50/20' : 'bg-white'
          }`}
        >
          <Checkbox
            checked={item.selected}
            onChange={() => toggleSelect(item.id)}
          />

          <img
            src={item.imageUrl || (item as any).image}
            alt={item.title}
            className="w-20 h-20 object-cover rounded border border-slate-100 shrink-0"
          />

          <div className="flex-1 min-w-0">
            <Link
              to="/item/$id"
              params={{ id: String(item.goodsId || item.id) }}
              className="text-xs text-slate-800 hover:text-red-600 line-clamp-2 no-underline"
            >
              {item.title}
            </Link>
            <div className="mt-1 flex items-center gap-2 text-[11px] text-slate-400">
              {item.shopName && <span>{item.shopName}</span>}
              {item.isSelfOperated && (
                <span className="bg-rose-50 text-red-600 px-1 rounded text-[10px]">
                  自营
                </span>
              )}
            </div>
          </div>

          <div className="text-right w-24">
            <div className="text-sm font-bold text-red-600">
              ¥{(item.price * item.quantity).toFixed(2)}
            </div>
            <div className="text-xs text-slate-400">¥{item.price.toFixed(2)} / 件</div>
          </div>

          <div className="w-28 flex justify-center">
            <InputNumber
              min={1}
              max={99}
              size="small"
              value={item.quantity}
              onChange={(val) => {
                const target = Number(val) || 1
                updateQuantity(item.id, target - item.quantity)
              }}
            />
          </div>

          <Button
            type="text"
            danger
            icon={<DeleteOutlined />}
            size="small"
            onClick={() => removeItem(item.id)}
          />
        </div>
      ))}
    </div>
  )
}

export default CartList
