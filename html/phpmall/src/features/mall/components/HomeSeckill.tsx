import React, { useState, useEffect } from 'react'
import { Link } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../../stores/cartStore'

const SECKILL_GOODS = [
  {
    id: 101,
    title: '户外GPS智能运动腕表 钛金属蓝宝石',
    tag: '限量秒杀',
    price: 2999,
    origPrice: 3699,
    progress: 82,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=300&q=80',
  },
  {
    id: 102,
    title: 'Hi-Res 无线主动降噪头戴耳机 40小时续航',
    tag: '爆款直降',
    price: 1299,
    origPrice: 1899,
    progress: 95,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&q=80',
  },
  {
    id: 103,
    title: '27英寸 4K 160Hz MiniLED 广色域专业显示器',
    tag: '热销秒杀',
    price: 1899,
    origPrice: 2499,
    progress: 64,
    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&q=80',
  },
  {
    id: 104,
    title: '无线三模热插拔机械键盘 RGB客制化手感',
    tag: '一件包邮',
    price: 349,
    origPrice: 499,
    progress: 76,
    image: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=300&q=80',
  },
  {
    id: 105,
    title: '深入理解计算机系统 + 算法导论 精装典藏版',
    tag: '每满100减50',
    price: 128,
    origPrice: 256,
    progress: 50,
    image: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&q=80',
  },
]

export const HomeSeckill: React.FC = () => {
  const addItem = useCartStore((state) => state.addItem)
  const [countdown, setCountdown] = useState({ hour: 1, minute: 45, second: 32 })

  useEffect(() => {
    const timer = setInterval(() => {
      setCountdown((prev) => {
        if (prev.second > 0) return { ...prev, second: prev.second - 1 }
        if (prev.minute > 0) return { ...prev, minute: 59, second: 59 }
        if (prev.hour > 0) return { hour: prev.hour - 1, minute: 59, second: 59 }
        return { hour: 2, minute: 0, second: 0 }
      })
    }, 1000)
    return () => clearInterval(timer)
  }, [])

  const formatDigit = (n: number) => String(n).padStart(2, '0')

  const handleAddToCart = (item: (typeof SECKILL_GOODS)[0]) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: '秒杀限时专享标配',
      shopName: '京东秒杀自营旗舰店',
    })
    message.success(`已将「${item.title}」加入购物车！`)
  }

  return (
    <section className="seckill-section w" id="sec-seckill">
      <div className="seckill-box">
        {/* 秒杀倒计时看板 */}
        <div className="seckill-countdown-panel">
          <div className="seckill-title-box">
            <h2 className="title">
              <Link to="/seckill" style={{ color: 'inherit', textDecoration: 'none' }}>
                京东秒杀
              </Link>
            </h2>
            <span className="subtitle">FLASH DEALS</span>
            <span className="bolt-icon">⚡</span>
          </div>
          <div className="round-desc">
            <span className="round-time" id="seckillRound">20:00</span> 点场 距结束还剩
          </div>
          <div className="timer-box">
            <span className="tim-item">{formatDigit(countdown.hour)}</span>
            <span className="tim-sep">:</span>
            <span className="tim-item">{formatDigit(countdown.minute)}</span>
            <span className="tim-sep">:</span>
            <span className="tim-item">{formatDigit(countdown.second)}</span>
          </div>
        </div>

        {/* 秒杀商品横向轮播滚动区 */}
        <div className="seckill-goods-slider">
          <div className="seckill-goods-track" id="seckillTrack">
            {SECKILL_GOODS.map((item) => (
              <div key={item.id} className="sk-item">
                <div className="sk-img-box">
                  <img src={item.image} alt={item.title} />
                  <span className="sk-tag">{item.tag}</span>
                </div>
                <h4 className="sk-title">{item.title}</h4>
                <div className="sk-price-wrap">
                  <span className="cur-price">
                    <small>¥</small>{item.price.toLocaleString()}
                  </span>
                  <span className="orig-price">¥{item.origPrice.toLocaleString()}</span>
                </div>
                <div className="sk-progress">
                  <div className="progress-bar">
                    <div className="fill" style={{ width: `${item.progress}%` }}></div>
                  </div>
                  <span className="progress-txt">已抢{item.progress}%</span>
                </div>
                <button
                  type="button"
                  className="sk-add-btn"
                  onClick={() => handleAddToCart(item)}
                >
                  立即抢购
                </button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  )
}
