import React, { useState, useEffect } from 'react'
import { Link } from '@tanstack/react-router'
import { Progress, Button, message } from 'antd'
import { ThunderboltFilled, ShoppingCartOutlined, FireOutlined } from '@ant-design/icons'
import { useCartStore } from '../../../stores/cartStore'

const SECKILL_PRODUCTS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属',
    price: 7999,
    originalPrice: 8999,
    soldPercent: 88,
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400&q=80',
    sku: '原色钛金属 / 256GB',
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 无线降噪头戴耳机',
    price: 1899,
    originalPrice: 2499,
    soldPercent: 72,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
    sku: '经典铂金银 / 官方标配',
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能运动户外手表',
    price: 4999,
    originalPrice: 6299,
    soldPercent: 95,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&q=80',
    sku: '49mm钛金属 / 高山回环',
  },
  {
    id: 4,
    title: '富士 X-T5 复古微单数码相机套机 (16-80mm)',
    price: 11999,
    originalPrice: 13999,
    soldPercent: 60,
    image: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=400&q=80',
    sku: '经典银黑配色 / 变焦套机',
  },
]

export const HomeSeckill: React.FC = () => {
  const addItem = useCartStore((state) => state.addItem)

  // 倒计时状态：时、分、秒
  const [timeLeft, setTimeLeft] = useState({ hours: 2, minutes: 45, seconds: 30 })

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev.seconds > 0) {
          return { ...prev, seconds: prev.seconds - 1 }
        }
        if (prev.minutes > 0) {
          return { ...prev, minutes: 59, seconds: 59 }
        }
        if (prev.hours > 0) {
          return { hours: prev.hours - 1, minutes: 59, seconds: 59 }
        }
        return { hours: 2, minutes: 0, seconds: 0 }
      })
    }, 1000)
    return () => clearInterval(timer)
  }, [])

  const handleQuickAdd = (p: (typeof SECKILL_PRODUCTS)[0], e: React.MouseEvent) => {
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

  const formatNum = (num: number) => String(num).padStart(2, '0')

  return (
    <section id="seckill" className="max-w-7xl mx-auto px-4 my-6">
      <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col md:flex-row">
        {/* 左侧：秒杀倒计时标牌 */}
        <div className="bg-gradient-to-br from-rose-600 to-red-700 text-white p-6 md:w-56 shrink-0 flex flex-col justify-between items-center text-center">
          <div>
            <div className="flex items-center justify-center gap-1.5 text-2xl font-black tracking-wider">
              <ThunderboltFilled className="text-amber-300" />
              <span>京东秒杀</span>
            </div>
            <div className="text-xs text-rose-100 font-medium mt-1">FLASH DEALS</div>
          </div>

          <div className="my-6">
            <div className="text-xs text-rose-100 font-medium mb-2">本场距结束还剩</div>
            <div className="flex items-center justify-center gap-1.5 text-base font-bold font-mono">
              <span className="bg-slate-900/90 text-white px-2 py-1 rounded">
                {formatNum(timeLeft.hours)}
              </span>
              <span>:</span>
              <span className="bg-slate-900/90 text-white px-2 py-1 rounded">
                {formatNum(timeLeft.minutes)}
              </span>
              <span>:</span>
              <span className="bg-slate-900/90 text-white px-2 py-1 rounded">
                {formatNum(timeLeft.seconds)}
              </span>
            </div>
          </div>

          <Link
            to="/list"
            className="text-xs text-white/90 hover:text-white underline underline-offset-4"
          >
            查看更多秒杀商品 &gt;
          </Link>
        </div>

        {/* 右侧：秒杀商品横滑网格 */}
        <div className="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 p-4">
          {SECKILL_PRODUCTS.map((prod) => (
            <Link
              key={prod.id}
              to="/item/$id"
              params={{ id: String(prod.id) }}
              className="p-3 flex flex-col justify-between group hover:bg-slate-50/60 rounded-lg transition"
            >
              <div className="relative overflow-hidden rounded-lg aspect-square bg-slate-100 mb-3">
                <img
                  src={prod.image}
                  alt={prod.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                <span className="absolute top-2 left-2 bg-rose-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow">
                  秒杀限时降
                </span>
              </div>

              <div>
                <h3 className="text-xs text-slate-800 font-medium line-clamp-2 group-hover:text-rose-600 transition-colors h-8">
                  {prod.title}
                </h3>

                <div className="mt-3 flex items-baseline gap-2">
                  <span className="text-base font-black text-rose-600">
                    ¥{prod.price.toFixed(2)}
                  </span>
                  <span className="text-xs text-slate-400 line-through">
                    ¥{prod.originalPrice.toFixed(2)}
                  </span>
                </div>

                {/* 抢购进度条 */}
                <div className="mt-2">
                  <div className="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                    <span>已抢 {prod.soldPercent}%</span>
                    <span className="text-rose-600 font-semibold flex items-center gap-0.5">
                      <FireOutlined /> 疯抢中
                    </span>
                  </div>
                  <Progress
                    percent={prod.soldPercent}
                    showInfo={false}
                    strokeColor={{ '0%': '#f87171', '100%': '#e11d48' }}
                    size="small"
                  />
                </div>
              </div>

              <div className="mt-4 pt-2 border-t border-slate-100">
                <Button
                  type="primary"
                  danger
                  icon={<ShoppingCartOutlined />}
                  block
                  onClick={(e) => handleQuickAdd(prod, e)}
                  className="text-xs font-semibold"
                >
                  立即抢购
                </Button>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}
