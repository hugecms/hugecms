import React from 'react'
import { Link } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../../stores/cartStore'

const FEED_GOODS = [
  {
    id: 301,
    title: '透气减震专业竞速跑鞋 男款马拉松跑鞋',
    tags: ['满300减50', '次日达'],
    price: 289,
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80',
  },
  {
    id: 302,
    title: '自动机械商务男士腕表 50米生活防水',
    tags: ['PLUS专享95折'],
    price: 1580,
    image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80',
  },
  {
    id: 303,
    title: '补水修护赋活水乳套装 敏感肌温和配方',
    tags: ['买1送1', '赠定制化妆包'],
    price: 399,
    image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80',
  },
  {
    id: 304,
    title: 'PBT五面热升华个性主题键帽 128键大全套',
    tags: ['限时立减20'],
    price: 169,
    image: 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&q=80',
  },
  {
    id: 305,
    title: '旗舰级双芯主动降噪立体声耳机 黑色经典款',
    tags: ['12期免息', '以旧换新'],
    price: 1799,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
  },
  {
    id: 306,
    title: '复古一次成像拍立得相机 附赠20张相纸礼盒',
    tags: ['节日送礼推荐'],
    price: 588,
    image: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400&q=80',
  },
  {
    id: 307,
    title: '英国梨与小苍兰浓香水 50ml 持久留香',
    tags: ['送定制试用装'],
    price: 620,
    image: 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=400&q=80',
  },
  {
    id: 308,
    title: '13.3英寸 超轻薄便携商务办公本 全天续航',
    tags: ['立省500', '晒单送原装包鼠'],
    price: 5299,
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&q=80',
  },
]

export const HomeFeed: React.FC = () => {
  const addItem = useCartStore((state) => state.addItem)

  const handleAddToCart = (item: (typeof FEED_GOODS)[0]) => {
    addItem({
      goodsId: item.id,
      title: item.title,
      price: item.price,
      quantity: 1,
      image: item.image,
      sku: '官方正品标配',
      shopName: '京东自营官方旗舰店',
    })
    message.success(`已将「${item.title}」加入购物车！`)
  }

  return (
    <section className="recommend-section w" id="sec-recommend">
      <div className="section-title-center">
        <span className="line"></span>
        <h2 className="title">为你推荐</h2>
        <span className="subtitle">EXPLORE RECOMMENDATIONS</span>
        <span className="line"></span>
      </div>
      <div className="recommend-grid" id="recommendGrid">
        {FEED_GOODS.map((item) => (
          <div key={item.id} className="goods-card">
            <Link to="/item/$id" params={{ id: String(item.id) }} className="thumb-wrap" style={{ display: 'block' }}>
              <img src={item.image} alt={item.title} />
              <span className="hover-find">找相似</span>
            </Link>
            <div className="card-info">
              <Link to="/item/$id" params={{ id: String(item.id) }} style={{ textDecoration: 'none', color: 'inherit' }}>
                <p className="g-title">
                  <span className="tag-zy">自营</span> {item.title}
                </p>
              </Link>
              <div className="g-tags">
                {item.tags.map((t) => (
                  <span key={t} className="promo-tag">{t}</span>
                ))}
              </div>
              <div className="g-bottom">
                <div className="g-price">
                  <span className="yen">¥</span>
                  <span className="amount">{item.price.toLocaleString()}</span>
                  <span className="decimals">.00</span>
                </div>
                <button
                  type="button"
                  className="add-cart-btn"
                  title="加入购物车"
                  onClick={() => handleAddToCart(item)}
                >
                  +
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </section>
  )
}
