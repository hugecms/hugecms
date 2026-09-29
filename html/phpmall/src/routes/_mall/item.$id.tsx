import React, { useState } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../stores/cartStore'
import styles from './item.module.css'

export const Route = createFileRoute('/_mall/item/$id')({
  component: ExactProductDetailPage,
})

const PRODUCT_DATA = {
  id: 1,
  title: 'Apple iPhone 16 Pro 256GB 原色钛金属 支持移动联通电信5G 双卡双待手机',
  slogan: '【年终钜惠】A18 Pro强悍芯片！4800万超广角微距，4K 120帧杜比视界，钛金属轻盈坚固！以旧换新至高补贴1000元！',
  basePrice: 7999,
  marketPrice: 8999,
  rating: '98%',
  comments: '50万+',
  code: '100086421992',
  images: [
    'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&q=80',
    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80',
    'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&q=80',
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
  ],
  colors: [
    { name: '原色钛金属', img: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=60&q=80' },
    { name: '沙漠钛金属', img: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=60&q=80' },
    { name: '白色钛金属', img: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=60&q=80' },
    { name: '黑色钛金属', img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=60&q=80' },
  ],
  versions: [
    { name: '128GB 标配版', price: 6999, market: 7999 },
    { name: '256GB 经典热销', price: 7999, market: 8999 },
    { name: '512GB 大容量', price: 9999, market: 10999 },
    { name: '1TB 顶配旗舰', price: 11999, market: 12999 },
  ],
  services: [
    { name: '官方标配 (送原厂快充线)', extra: 0 },
    { name: '+ ¥1498 官方AppleCare+ 两年轻微损坏只换不修', extra: 1498 },
  ],
}

function ExactProductDetailPage() {
  const navigate = useNavigate()
  const addItem = useCartStore((state) => state.addItem)

  const [activeThumb, setActiveThumb] = useState(0)
  const [selectedColor, setSelectedColor] = useState('原色钛金属')
  const [selectedVersion, setSelectedVersion] = useState(PRODUCT_DATA.versions[1])
  const [selectedService, setSelectedService] = useState(PRODUCT_DATA.services[0])
  const [quantity, setQuantity] = useState(1)
  const [activeDetailTab, setActiveDetailTab] = useState<'intro' | 'specs' | 'service' | 'comments'>('intro')

  const currentPrice = selectedVersion.price + selectedService.extra
  const currentMarket = selectedVersion.market + selectedService.extra

  const handleAddToCart = () => {
    addItem({
      goodsId: PRODUCT_DATA.id,
      title: PRODUCT_DATA.title,
      price: currentPrice,
      quantity,
      image: PRODUCT_DATA.images[activeThumb],
      sku: `${selectedColor} / ${selectedVersion.name} / ${selectedService.name.split(' ')[0]}`,
      shopName: 'Apple产品京东自营旗舰店',
    })
    message.success('已成功加入购物车！')
  }

  const handleBuyNow = () => {
    addItem({
      goodsId: PRODUCT_DATA.id,
      title: PRODUCT_DATA.title,
      price: currentPrice,
      quantity,
      image: PRODUCT_DATA.images[activeThumb],
      sku: `${selectedColor} / ${selectedVersion.name} / ${selectedService.name.split(' ')[0]}`,
      shopName: 'Apple产品京东自营旗舰店',
    })
    navigate({ to: '/checkout' })
  }

  return (
    <div style={{ backgroundColor: '#f4f4f4', paddingBottom: 40 }}>
        {/* 面包屑导航 */}
        <nav className={`w ${styles.breadcrumb}`}>
          <Link to="/">首页</Link>
          <span className={styles.sep}>&gt;</span>
          <Link to="/list" search={{ q: '手机' }}>手机数码</Link>
          <span className={styles.sep}>&gt;</span>
          <Link to="/list" search={{ q: '智能手机' }}>智能手机</Link>
          <span className={styles.sep}>&gt;</span>
          <Link to="/shop">Apple产品京东自营旗舰店</Link>
          <span className={styles.sep}>&gt;</span>
          <span className={styles.current}>Apple iPhone 16 Pro</span>
        </nav>

        {/* 商品核心交易信息区 */}
        <main className={`w ${styles.productIntro}`}>
          {/* 左侧：图片画廊 */}
          <div className={styles.previewWrap}>
            <div className={styles.mainImgBox}>
              <img src={PRODUCT_DATA.images[activeThumb]} alt="iPhone 16 Pro" />
            </div>

            {/* 缩略图选择栏 */}
            <div className={styles.thumbListWrap}>
              <button
                type="button"
                className={styles.thumbBtn}
                onClick={() => setActiveThumb((activeThumb - 1 + 4) % 4)}
              >
                &lt;
              </button>
              <div className={styles.thumbScroll}>
                <ul className={styles.thumbList}>
                  {PRODUCT_DATA.images.map((img, idx) => (
                    <li
                      key={idx}
                      className={activeThumb === idx ? styles.active : ''}
                      onMouseEnter={() => setActiveThumb(idx)}
                    >
                      <img src={img} alt="thumb" />
                    </li>
                  ))}
                </ul>
              </div>
              <button
                type="button"
                className={styles.thumbBtn}
                onClick={() => setActiveThumb((activeThumb + 1) % 4)}
              >
                &gt;
              </button>
            </div>

            {/* 底部辅助功能 */}
            <div className={styles.previewActions}>
              <span>🔖 商品编号：{PRODUCT_DATA.code}</span>
              <span className={styles.actionBtn} onClick={() => message.info('已复制分享链接')}>
                🔗 分享
              </span>
              <span className={styles.actionBtn} onClick={() => message.success('已关注商品')}>
                ❤️ 关注商品 (12.8万)
              </span>
            </div>
          </div>

          {/* 右侧：商品购买信息面板 */}
          <div className={styles.itemInfoWrap}>
            <div className={styles.skuName}>
              <span className={styles.badgeZy}>自营次日达</span>
              <h1>{PRODUCT_DATA.title}</h1>
            </div>
            <p className={styles.skuSlogan}>{PRODUCT_DATA.slogan}</p>

            {/* 价格与促销区 */}
            <div className={styles.summaryPriceWrap}>
              <div className={styles.summaryTop}>
                <div className={styles.priceTitle}>京东秒杀</div>
                <div className={styles.priceBox}>
                  <span className={styles.yen}>¥</span>
                  <span className={styles.priceVal}>{currentPrice.toFixed(2)}</span>
                  <span className={styles.priceMarket}>
                    [<del>¥{currentMarket.toFixed(2)}</del>]
                  </span>
                  <span className={styles.priceTag}>直降1000</span>
                </div>
                <div className={styles.commentCountBox}>
                  <p className={styles.txt}>累计评价</p>
                  <p className={styles.count}>{PRODUCT_DATA.comments}</p>
                  <p className={styles.rate}>好评率 {PRODUCT_DATA.rating}</p>
                </div>
              </div>

              <div className={styles.summaryRow}>
                <span className={styles.label}>促 销</span>
                <div className={styles.promoItems}>
                  <div className={styles.promoLine}>
                    <span className={styles.pTag}>跨店满减</span>
                    <span className={styles.pText}>每满300减50，上不封顶</span>
                  </div>
                  <div className={styles.promoLine}>
                    <span className={styles.pTag}>以旧换新</span>
                    <span className={styles.pText}>旧机回收至高额外补贴 1000 元红包</span>
                  </div>
                </div>
              </div>

              <div className={styles.summaryRow}>
                <span className={styles.label}>领 券</span>
                <div className={styles.couponBadges}>
                  <span className={styles.cBadge} onClick={() => message.success('已领取 400 元专享券')}>
                    满5000减400
                  </span>
                  <span className={styles.cBadge} onClick={() => message.success('已领取 200 元满减券')}>
                    满3000减200
                  </span>
                  <span className={styles.cBadge} onClick={() => message.success('已领取 50 元配件券')}>
                    满1000减50
                  </span>
                </div>
              </div>
            </div>

            {/* 履约与配送 */}
            <div className={styles.summaryServiceWrap}>
              <div className={styles.summaryRow}>
                <span className={styles.label}>配 送 至</span>
                <div className={styles.addressSelect}>
                  <span>北京市 朝阳区 三环到四环之间 </span>
                  <strong className={styles.stockStatus}>有货</strong>
                  <p className={styles.deliverPromise}>
                    23:00 前完成下单，预计<strong>明天(次日)</strong>送达，由{' '}
                    <Link to="/shop" style={{ color: '#e1251b', fontWeight: 'bold' }}>
                      Apple产品京东自营旗舰店
                    </Link>{' '}
                    提供发货并提供售后服务。
                  </p>
                </div>
              </div>
              <div className={styles.summaryRow}>
                <span className={styles.label}>增值保障</span>
                <div className={styles.serviceAssure}>
                  <span>✓ 7天无理由退货</span>
                  <span>✓ 211限时达</span>
                  <span>✓ 原厂保修一年</span>
                  <span>✓ 正品溯源</span>
                </div>
              </div>
            </div>

            {/* SKU规格选择区 */}
            <div className={styles.summarySkuWrap}>
              {/* 颜色选择 */}
              <div className={styles.skuRow}>
                <span className={styles.label}>选择颜色</span>
                <div className={styles.skuOptions}>
                  {PRODUCT_DATA.colors.map((c) => (
                    <button
                      key={c.name}
                      type="button"
                      className={`${styles.optBtn} ${selectedColor === c.name ? styles.optBtnActive : ''}`}
                      onClick={() => setSelectedColor(c.name)}
                    >
                      <img src={c.img} alt={c.name} />
                      <span>{c.name}</span>
                    </button>
                  ))}
                </div>
              </div>

              {/* 容量选择 */}
              <div className={styles.skuRow}>
                <span className={styles.label}>选择版本</span>
                <div className={styles.skuOptions}>
                  {PRODUCT_DATA.versions.map((v) => (
                    <button
                      key={v.name}
                      type="button"
                      className={`${styles.optBtn} ${selectedVersion.name === v.name ? styles.optBtnActive : ''}`}
                      onClick={() => setSelectedVersion(v)}
                    >
                      {v.name}
                    </button>
                  ))}
                </div>
              </div>

              {/* 服务套餐 */}
              <div className={styles.skuRow}>
                <span className={styles.label}>增值服务</span>
                <div className={styles.skuOptions}>
                  {PRODUCT_DATA.services.map((s) => (
                    <button
                      key={s.name}
                      type="button"
                      className={`${styles.optBtn} ${selectedService.name === s.name ? styles.optBtnActive : ''}`}
                      onClick={() => setSelectedService(s)}
                    >
                      {s.name}
                    </button>
                  ))}
                </div>
              </div>

              {/* 数量步进器与购买动作 */}
              <div className={styles.buyActionRow}>
                <div className={styles.quantityBox}>
                  <input type="text" value={quantity} readOnly />
                  <div className={styles.btnGroup}>
                    <button type="button" onClick={() => setQuantity(quantity + 1)}>
                      +
                    </button>
                    <button type="button" onClick={() => setQuantity(Math.max(1, quantity - 1))}>
                      -
                    </button>
                  </div>
                </div>

                <button
                  type="button"
                  className={styles.btnAddCart}
                  onClick={handleAddToCart}
                >
                  加入购物车
                </button>
                <button
                  type="button"
                  className={styles.btnBuyNow}
                  onClick={handleBuyNow}
                >
                  立即购买
                </button>
              </div>

              <div className={styles.safeTips}>
                <span>温馨提示：支持7天无理由退货 (激活后不支持无理由退货)</span>
              </div>
            </div>
          </div>
        </main>

        {/* 下方双栏：左侧店铺信息 + 右侧详情Tab */}
        <section className={`w ${styles.detailLayoutWrap}`}>
          <aside className={styles.asideStoreInfo}>
            <div className={styles.storeHead}>
              <h3 className={styles.storeTitle}>Apple产品京东自营旗舰店</h3>
              <span className={styles.badgeZy}>自营官方</span>
            </div>
            <div className={styles.storeScores}>
              <p>商品评价：<strong>4.9 高</strong></p>
              <p>物流时效：<strong>4.9 高</strong></p>
              <p>售后服务：<strong>4.9 高</strong></p>
            </div>
            <div className={styles.storeBtns}>
              <Link to="/shop" className={styles.btnEnterStore}>进入店铺</Link>
              <button
                type="button"
                className={styles.btnFollowStore}
                onClick={() => message.success('已关注店铺')}
              >
                关注店铺
              </button>
            </div>
          </aside>

          <div className={styles.mainDetailContent}>
            <div className={styles.detailTabHeader}>
              <button
                type="button"
                className={`${styles.dTabBtn} ${activeDetailTab === 'intro' ? styles.dTabBtnActive : ''}`}
                onClick={() => setActiveDetailTab('intro')}
              >
                商品介绍
              </button>
              <button
                type="button"
                className={`${styles.dTabBtn} ${activeDetailTab === 'specs' ? styles.dTabBtnActive : ''}`}
                onClick={() => setActiveDetailTab('specs')}
              >
                规格与包装
              </button>
              <button
                type="button"
                className={`${styles.dTabBtn} ${activeDetailTab === 'service' ? styles.dTabBtnActive : ''}`}
                onClick={() => setActiveDetailTab('service')}
              >
                售后保障
              </button>
              <button
                type="button"
                className={`${styles.dTabBtn} ${activeDetailTab === 'comments' ? styles.dTabBtnActive : ''}`}
                onClick={() => setActiveDetailTab('comments')}
              >
                商品评价 (50万+)
              </button>
            </div>

            <div className={styles.tabPaneContent}>
              {activeDetailTab === 'intro' && (
                <div style={{ padding: '20px 0' }}>
                  <p style={{ lineHeight: '1.8', color: '#666', marginBottom: '16px' }}>
                    搭载全新一代 A18 Pro 芯片，配备新一代 6 核图形处理器，光线追踪性能提升至高可达 2 倍。
                    支持 4800 万像素超广角、4K 120 帧杜比视界摄影，航空级钛金属边框坚固轻盈。
                  </p>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' }}>
                    <img
                      src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&q=80"
                      alt="detail1"
                      style={{ borderRadius: '8px', width: '100%' }}
                    />
                    <img
                      src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80"
                      alt="detail2"
                      style={{ borderRadius: '8px', width: '100%' }}
                    />
                  </div>
                </div>
              )}

              {activeDetailTab === 'specs' && (
                <div style={{ padding: '20px', lineHeight: '2.4', color: '#444' }}>
                  <p><strong>CPU型号：</strong>A18 Pro 6核仿生芯片</p>
                  <p><strong>屏幕尺寸：</strong>6.3英寸 超视网膜 XDR OLED 显示屏</p>
                  <p><strong>后置摄像头：</strong>4800万主摄 + 4800万超广角 + 1200万5倍长焦</p>
                  <p><strong>包装清单：</strong>iPhone 16 Pro 手机本体 × 1、USB-C 充电线 × 1、说明书与保修卡 × 1</p>
                </div>
              )}

              {activeDetailTab === 'service' && (
                <div style={{ padding: '20px', lineHeight: '2', color: '#555' }}>
                  <h4 style={{ color: '#e1251b', fontWeight: 'bold' }}>京东自营正品售后保障</h4>
                  <p>本商品质保周期为 1 年质保，在此时间范围内可提交维修申请，由京东售后上门取件并送交 Apple 官方检修。</p>
                </div>
              )}

              {activeDetailTab === 'comments' && (
                <div style={{ padding: '20px' }}>
                  <div
                    style={{
                      display: 'flex',
                      alignItems: 'center',
                      gap: '40px',
                      paddingBottom: '20px',
                      borderBottom: '1px solid #f0f0f0',
                    }}
                  >
                    <div>
                      <div style={{ fontSize: '36px', fontWeight: 'bold', color: '#e1251b' }}>98%</div>
                      <div style={{ color: '#999', fontSize: '12px' }}>好评度</div>
                    </div>
                    <div style={{ color: '#666', fontSize: '13px' }}>
                      <p>买家印象：质感高级 (12.4万) • 运行流畅 (9.8万) • 拍照震撼 (8.5万)</p>
                    </div>
                  </div>
                  <div style={{ paddingTop: '16px', lineHeight: '1.8' }}>
                    <p><strong>j***8 (PLUS会员)：</strong>原色钛金属非常耐看，手感轻盈很多，拍照快门实体键很方便，微距绝了！</p>
                    <p style={{ color: '#999', fontSize: '11px' }}>2026-09-28 购买版本: 原色钛金属 256GB</p>
                  </div>
                </div>
              )}
            </div>
          </div>
        </section>
      </div>
  )
}
