import React, { useState, useEffect } from 'react'
import { Link } from '@tanstack/react-router'

const SUB_CATEGORIES_DATA: Record<number, { tags: string[]; groups: { title: string; items: string[] }[] }> = {
  0: {
    tags: ['电视影音', '冰箱洗衣机', '空调特惠', '厨房大电', '净水设备', '热水器'],
    groups: [
      { title: '电视', items: ['OLED超薄', 'MiniLED', '8K超清', '游戏电视', '激光影院', '智慧屏'] },
      { title: '空调', items: ['新一级能效', '变频立式', '中央空调', '风管机', '静音节能'] },
      { title: '厨房小电', items: ['空气炸锅', '电饭煲', '破壁机', '咖啡机', '微波炉', '养生壶'] },
      { title: '生活电器', items: ['扫地机器人', '洗地机', '吸尘器', '空气净化器', '除湿机', '挂烫机'] },
    ],
  },
  1: {
    tags: ['新品手机', '以旧换新', '5G智能机', '折叠屏', '电竞游戏机', '快充配件'],
    groups: [
      { title: '热门手机', items: ['iPhone 16 Pro', '华为 Mate 70', '小米 15', '荣耀 Magic', 'vivo X200', 'OPPO Find'] },
      { title: '数码影音', items: ['无线降噪耳机', '运动蓝牙', '微单相机', '单反镜头', '运动相机', '拍立得'] },
      { title: '智能穿戴', items: ['智能手表', '健康手环', 'VR/AR眼镜', '儿童手表', '专业心率带'] },
    ],
  },
  2: {
    tags: ['轻薄本', '游戏本', '台式组装机', '机械键盘', '曲面电竞屏', 'AI电脑'],
    groups: [
      { title: '电脑整机', items: ['ThinkPad', 'ROG玩家国度', 'MacBook Air', '一体机', '迷你主机', '服务器'] },
      { title: '电脑配件', items: ['RTX 4090显卡', '英特尔酷睿', 'DDR5内存', '高速NVMe固态', '水冷散热器'] },
      { title: '办公外设', items: ['激光打印机', '投影仪', '扫描仪', '人体工学椅', '升降桌', '碎纸机'] },
    ],
  },
}

function getSubData(index: number) {
  if (SUB_CATEGORIES_DATA[index]) return SUB_CATEGORIES_DATA[index]
  return {
    tags: ['品质爆款', '今日免息', '热卖榜TOP', '满减特惠', '好评精选'],
    groups: [
      { title: '热门推荐', items: ['畅销精选', '自营直发', '高口碑好物', '抢先体验', '新人立减'] },
      { title: '特色品类', items: ['大牌专区', '进口精选', '限时折扣', '正品包邮', '售后无忧'] },
      { title: '场景选购', items: ['居家生活', '送礼推荐', '办公实用', '户外运动', '精致护理'] },
    ],
  }
}

const CATEGORY_NAMES = [
  '家用电器',
  '手机 / 运营商 / 数码',
  '电脑 / 办公 / 文具',
  '家居 / 家具 / 家装 / 厨具',
  '男装 / 女装 / 童装 / 内衣',
  '美妆 / 个人清洁 / 宠物',
  '女鞋 / 箱包 / 钟表 / 珠宝',
  '男鞋 / 运动 / 户外装备',
  '房产 / 汽车 / 汽车用品',
  '母婴 / 玩具乐器',
  '食品 / 酒类 / 生鲜 / 特产',
  '艺术 / 礼品鲜花 / 农资绿植',
  '医药保健 / 计生情趣',
  '图书 / 文娱 / 电子书',
]

const SLIDES = [
  {
    bg: 'linear-gradient(135deg, #1b263b 0%, #0d1b2a 100%)',
    badge: '新品首发',
    title: '旗舰影像 破晓新生',
    desc: '超高动态范围 · 徕卡光学镜头 · 限时立省 600 元',
    btnText: '立即选购 ›',
    img: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600&q=80',
    link: '/item/1',
  },
  {
    bg: 'linear-gradient(135deg, #4a0e17 0%, #1e0008 100%)',
    badge: '年终盛典',
    title: '全屋智能家电狂欢',
    desc: '一级能效立享政府补贴20% · 免费上门以旧换新',
    btnText: '领取家电补贴 ›',
    img: 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600&q=80',
    link: '/coupon',
  },
  {
    bg: 'linear-gradient(135deg, #0b3c5d 0%, #1d2731 100%)',
    badge: '酷玩电竞',
    title: '高刷电竞本 战力全开',
    desc: 'RTX 4080 显卡加持 · 240Hz 超感屏 · 赠专属外设',
    btnText: '去电竞专区 ›',
    img: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&q=80',
    link: '/list',
  },
]

export const HomeHero: React.FC = () => {
  const [hoveredCatIndex, setHoveredCatIndex] = useState<number | null>(null)
  const [currentSlide, setCurrentSlide] = useState(0)
  const [newsTab, setNewsTab] = useState<'news' | 'notice'>('news')

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % SLIDES.length)
    }, 4500)
    return () => clearInterval(timer)
  }, [])

  const activeSubData = hoveredCatIndex !== null ? getSubData(hoveredCatIndex) : null

  return (
    <main className="main-hero">
      <div className="w hero-layout">
        {/* 左侧：细分类目树导航 */}
        <aside
          className="category-menu"
          id="categoryMenu"
          onMouseLeave={() => setHoveredCatIndex(null)}
        >
          <ul className="cat-list">
            {CATEGORY_NAMES.map((name, idx) => (
              <li
                key={name}
                className={`cat-item ${hoveredCatIndex === idx ? 'active' : ''}`}
                onMouseEnter={() => setHoveredCatIndex(idx)}
              >
                <Link to="/list" search={{ q: name.split('/')[0].trim() }}>
                  {name}
                </Link>
                <span className="cat-arrow">›</span>
              </li>
            ))}
          </ul>

          {/* 悬浮弹出的详细子分类抽屉面板 */}
          {hoveredCatIndex !== null && activeSubData && (
            <div
              className="category-sub-panel"
              id="categorySubPanel"
              style={{ display: 'block' }}
              onMouseEnter={() => setHoveredCatIndex(hoveredCatIndex)}
              onMouseLeave={() => setHoveredCatIndex(null)}
            >
              <div className="sub-tags-header">
                {activeSubData.tags.map((tag) => (
                  <Link key={tag} to="/list" search={{ q: tag }} className="sub-tag-btn">
                    {tag} ›
                  </Link>
                ))}
              </div>
              <div className="sub-detail-groups">
                {activeSubData.groups.map((group) => (
                  <div key={group.title} className="sub-group-row">
                    <div className="sub-group-key">{group.title} ›</div>
                    <div className="sub-group-values">
                      {group.items.map((it) => (
                        <Link key={it} to="/list" search={{ q: it }}>
                          {it}
                        </Link>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </aside>

        {/* 中间：大屏主轮播 + 底部双拼特惠 */}
        <section className="hero-slider-wrap">
          <div className="slider-box" id="mainSlider">
            <div className="slide-list">
              {SLIDES.map((slide, idx) => (
                <div
                  key={slide.title}
                  className={`slide-item ${idx === currentSlide ? 'active' : ''}`}
                  style={{ background: slide.bg }}
                >
                  <div className="slide-content">
                    <span className="badge">{slide.badge}</span>
                    <h2>{slide.title}</h2>
                    <p>{slide.desc}</p>
                    <Link to={slide.link} className="slide-btn" style={{ display: 'inline-block', textAlign: 'center' }}>
                      {slide.btnText}
                    </Link>
                  </div>
                  <img src={slide.img} alt={slide.title} className="slide-img" />
                </div>
              ))}
            </div>
            {/* 左右切换 */}
            <button
              type="button"
              className="slider-arrow prev"
              id="sliderPrev"
              onClick={() => setCurrentSlide((prev) => (prev - 1 + SLIDES.length) % SLIDES.length)}
            >
              ‹
            </button>
            <button
              type="button"
              className="slider-arrow next"
              id="sliderNext"
              onClick={() => setCurrentSlide((prev) => (prev + 1) % SLIDES.length)}
            >
              ›
            </button>
            {/* 指示圆点 */}
            <div className="slider-dots" id="sliderDots">
              {SLIDES.map((_, idx) => (
                <span
                  key={idx}
                  className={`dot ${idx === currentSlide ? 'active' : ''}`}
                  onClick={() => setCurrentSlide(idx)}
                />
              ))}
            </div>
          </div>

          {/* 轮播图下方的并排双拼促销卡位 */}
          <div className="hero-sub-promos">
            <div className="promo-card">
              <div className="promo-text">
                <h3>超市爆款榜</h3>
                <p>好物低至 9.9 包邮</p>
                <Link to="/list" search={{ q: '超市爆款' }} className="promo-link">
                  去逛逛 ›
                </Link>
              </div>
              <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=160&q=80" alt="特惠个护" />
            </div>
            <div className="promo-card">
              <div className="promo-text">
                <h3>数码潮店</h3>
                <p>无线头戴耳机直降</p>
                <Link to="/item/2" className="promo-link">
                  立即抢 ›
                </Link>
              </div>
              <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=160&q=80" alt="影音数码" />
            </div>
          </div>
        </section>

        {/* 右侧：个人卡片 + 资讯快报 + 便民服务网格 */}
        <aside className="hero-user-sidebar">
          {/* 用户卡片 */}
          <div className="user-card">
            <div className="user-header">
              <div className="avatar-box">
                <img
                  src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&q=80"
                  alt="用户头像"
                  className="avatar-img"
                />
              </div>
              <div className="user-greet">
                <p className="greet-title">Hi~ 欢迎逛京东！</p>
                <div className="user-actions">
                  <Link to="/login" className="btn-login">登录</Link>
                  <Link to="/register" className="btn-reg">注册</Link>
                </div>
              </div>
            </div>
            <div className="user-benefits">
              <Link to="/register" className="benefit-item">
                <span className="b-tag">新人专享</span>
                <span>百元大礼包</span>
              </Link>
              <Link to="/plus" className="benefit-item plus">
                <span className="b-tag plus-tag">PLUS</span>
                <span>全年立省¥1128</span>
              </Link>
            </div>
          </div>

          {/* 京东快报 / 资讯公告 */}
          <div className="jd-news">
            <div className="news-tab-header">
              <span
                className={`news-tab ${newsTab === 'news' ? 'active' : ''}`}
                onClick={() => setNewsTab('news')}
              >
                JD 快报
              </span>
              <span
                className={`news-tab ${newsTab === 'notice' ? 'active' : ''}`}
                onClick={() => setNewsTab('notice')}
              >
                商城公告
              </span>
              <a href="javascript:;" className="news-more">更多 ›</a>
            </div>
            <ul className="news-list" id="newsList">
              {newsTab === 'news' ? (
                <>
                  <li><span className="tag">特惠</span><a href="javascript:;">家电数码新一轮政府补贴开启预约！</a></li>
                  <li><span className="tag">热门</span><a href="javascript:;">京东物流发布春节“不打烊”服务承诺</a></li>
                  <li><span className="tag">活动</span><a href="javascript:;">大牌手机至高24期免息券限量抢</a></li>
                  <li><span className="tag">公益</span><a href="javascript:;">“暖冬计划”持续推进：爱心物资抵达</a></li>
                </>
              ) : (
                <>
                  <li><span className="tag">公告</span><a href="javascript:;">关于平台支付通道例行升级维护通知</a></li>
                  <li><span className="tag">规则</span><a href="javascript:;">关于保障春节假期的物流配送与履约规则</a></li>
                  <li><span className="tag">防诈</span><a href="javascript:;">关于防范冒充客服短信诈骗的安全预警公告</a></li>
                  <li><span className="tag">服务</span><a href="javascript:;">京麦商家端全新发货工作台升级指引</a></li>
                </>
              )}
            </ul>
          </div>

          {/* 便民生活服务网格 */}
          <div className="convenience-services">
            <div className="service-grid">
              <a href="javascript:;" className="service-item">
                <span className="icon">📱</span>
                <span className="title">话费充值</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">✈️</span>
                <span className="title">机票预订</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">🏨</span>
                <span className="title">酒店特惠</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">🎬</span>
                <span className="title">电影票</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">⛽</span>
                <span className="title">加油卡</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">🎮</span>
                <span className="title">游戏充值</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">💳</span>
                <span className="title">白条分期</span>
              </a>
              <a href="javascript:;" className="service-item">
                <span className="icon">🎁</span>
                <span className="title">京东卡券</span>
              </a>
            </div>
          </div>
        </aside>
      </div>
    </main>
  )
}
