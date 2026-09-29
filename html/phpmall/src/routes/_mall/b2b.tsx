import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { message } from 'antd'
import { useCartStore } from '../../stores/cartStore'
import styles from './b2b.module.css'

export const Route = createFileRoute('/_mall/b2b')({
  component: B2BProcurementPage,
})

const B2B_PRODUCTS = [
  {
    id: 101,
    title: '联想 ThinkPad T14p 商务轻薄笔记本电脑 (i7 32G 1TB 集成大宗授权)',
    retailPrice: 8999,
    tag: '大宗起采: 5台起',
    tiers: [
      { count: '5~19 台', price: '¥8,299 /台' },
      { count: '20~49 台', price: '¥7,799 /台' },
      { count: '≥ 50 台 (企业底价)', price: '¥7,299 /台', isBottom: true },
    ],
    lowestPrice: '¥7,299.00',
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=360&q=80',
    sku: 'i7 32G 1TB',
  },
  {
    id: 102,
    title: 'MAXHUB 75英寸 4K 智能会议平板一体机 (无线投屏/视频会议双系统)',
    retailPrice: 15999,
    tag: '大宗起采: 2台起',
    tiers: [
      { count: '2~4 台', price: '¥14,200 /台' },
      { count: '5~9 台', price: '¥13,500 /台' },
      { count: '≥ 10 台 (企业底价)', price: '¥12,800 /台', isBottom: true },
    ],
    lowestPrice: '¥12,800.00',
    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=360&q=80',
    sku: '75英寸 4K',
  },
  {
    id: 103,
    title: '得力（deli）多功能商务激光一体打印机 (双面打印/复印/高速扫描)',
    retailPrice: 2499,
    tag: '大宗起采: 3台起',
    tiers: [
      { count: '3~9 台', price: '¥2,199 /台' },
      { count: '10~29 台', price: '¥1,999 /台' },
      { count: '≥ 30 台 (企业底价)', price: '¥1,799 /台', isBottom: true },
    ],
    lowestPrice: '¥1,799.00',
    image: 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=360&q=80',
    sku: '全功能旗舰款',
  },
  {
    id: 104,
    title: '京东E卡 经典电子卡 500元面值 (员工中秋/年节福利/即时绑定可用)',
    retailPrice: 500,
    tag: '大宗起采: 50张起',
    tiers: [
      { count: '50~199 张', price: '¥492 /张 (9.84折)' },
      { count: '200~499 张', price: '¥485 /张 (9.70折)' },
      { count: '≥ 500 张 (批量专属)', price: '¥475 /张 (9.50折)', isBottom: true },
    ],
    lowestPrice: '¥475.00',
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=360&q=80',
    sku: '500元面值',
  },
]

function B2BProcurementPage() {
  const [company, setCompany] = useState('')
  const [cat, setCat] = useState('商用办公数码')
  const [budget, setBudget] = useState('10万~50万元')
  const [phone, setPhone] = useState('')
  const addItem = useCartStore((state) => state.addItem)

  const handleSubmitQuote = (e: React.FormEvent) => {
    e.preventDefault()
    if (!company || !phone) {
      message.error('请填写完整企业名称与联系手机')
      return
    }
    message.success(`采购意向已提报成功！资深客户经理将在 2 小时内联系 ${phone}`)
    setCompany('')
    setPhone('')
  }

  const handleOrder = (item: (typeof B2B_PRODUCTS)[0]) => {
    addItem({
      goodsId: item.id,
      title: `[企业集采] ${item.title}`,
      price: item.retailPrice,
      quantity: 5,
      image: item.image,
      sku: item.sku,
      shopName: '京东企业购大宗直发中心',
    })
    message.success(`已按阶梯起购量将「${item.title}」加入企业采购车！`)
  }

  return (
    <div className={styles.b2bBody}>
      <div>
        {/* 顶部企业购专属 Header */}
        <header className={styles.b2bHeader}>
          <div className={`w ${styles.b2bHeaderInner}`}>
            <div className={styles.b2bLogoBrand}>
              <Link to="/b2b" className={styles.b2bLogoIcon}>
                JD
              </Link>
              <div className={styles.b2bLogoText}>
                <h2>京东企业购</h2>
                <p>ENTERPRISE PROCUREMENT · 数字化采购一站式综合解决方案</p>
              </div>
            </div>
            <nav className={styles.b2bNavLinks}>
              <Link to="/b2b" className="active">企业购首页</Link>
              <button type="button">商用办公</button>
              <button type="button">员工福利与礼品卡</button>
              <button type="button">工业品 MRO</button>
              <button type="button">企业金采账期</button>
              <button
                type="button"
                onClick={() => {
                  const el = document.getElementById('b2bQuoteForm')
                  el?.scrollIntoView({ behavior: 'smooth' })
                }}
                className={styles.btnB2bQuoteTop}
              >
                提报大宗采购单
              </button>
            </nav>
          </div>
        </header>

        {/* 企业采购 Hero 大屏 Banner */}
        <section className={styles.b2bHeroBanner}>
          <div className={`w ${styles.heroContent}`}>
            <div className={styles.heroText}>
              <span className={styles.heroTag}>2026 年度企业数字化采购专项扶持</span>
              <h1 className={styles.heroTitle}>全品类大宗集采 直享阶梯出厂底价</h1>
              <p className={styles.heroDesc}>
                专为中小企业、集团客户及事业单位打造。正规 13% 增值税专票一键开具、支持 30-90 天对公免息账期，京东全国八大仓一体化智能配送上门。
              </p>
              <div className={styles.heroStatsRow}>
                <div className={styles.statItem}>
                  <div className={styles.num}>800万+</div>
                  <div className={styles.label}>入驻注册认证企业</div>
                </div>
                <div className={styles.statItem}>
                  <div className={styles.num}>15%~35%</div>
                  <div className={styles.label}>大宗集采平均综合降本</div>
                </div>
                <div className={styles.statItem}>
                  <div className={styles.num}>1对1</div>
                  <div className={styles.label}>专属资深客户经理履约</div>
                </div>
              </div>
            </div>

            {/* 右侧快捷询价单卡片 */}
            <div className={styles.heroQuoteCard}>
              <div className={styles.quoteCardHead}>
                <h4>企业大宗采购极速询价</h4>
                <span>2小时内专人回电</span>
              </div>
              <form id="b2bQuoteForm" onSubmit={handleSubmitQuote}>
                <div className={styles.quoteFormItem}>
                  <label>企业主体名称 *</label>
                  <input
                    type="text"
                    className={styles.quoteInput}
                    placeholder="如：北京科技有限公司"
                    value={company}
                    onChange={(e) => setCompany(e.target.value)}
                    required
                  />
                </div>
                <div className={styles.quoteFormItem}>
                  <label>采购品类意向 *</label>
                  <select
                    className={styles.quoteSelect}
                    value={cat}
                    onChange={(e) => setCat(e.target.value)}
                  >
                    <option value="商用办公数码">商用办公数码 / 电脑会议大屏</option>
                    <option value="员工福利年节卡券">员工福利 / 京东E卡与节日礼品</option>
                    <option value="工业品与五金劳保">工业品 / MRO 与工具劳保</option>
                    <option value="企业日常消耗耗材">日常行政 / 打印纸与保洁日化</option>
                  </select>
                </div>
                <div className={styles.quoteFormItem}>
                  <label>预估采购预算 *</label>
                  <select
                    className={styles.quoteSelect}
                    value={budget}
                    onChange={(e) => setBudget(e.target.value)}
                  >
                    <option value="5万~10万元">5万 ~ 10 万元</option>
                    <option value="10万~50万元">10万 ~ 50 万元</option>
                    <option value="50万~200万元">50万 ~ 200 万元</option>
                    <option value="200万元以上">200 万元以上 (战略大客户专线)</option>
                  </select>
                </div>
                <div className={styles.quoteFormItem}>
                  <label>联系人对接手机 *</label>
                  <input
                    type="tel"
                    className={styles.quoteInput}
                    placeholder="请输入采购对接人手机号"
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                    required
                  />
                </div>
                <button type="submit" className={styles.btnSubmitQuote}>
                  提交询价意向 · 享专属折扣
                </button>
              </form>
            </div>
          </div>
        </section>

        {/* 4大专享权益金刚区 */}
        <div className={`w ${styles.b2bBenefitsStrip}`}>
          <div className={styles.benefitBox}>
            <div className={styles.bIcon}>📑</div>
            <div className={styles.bText}>
              <h5>合规 13% 专票</h5>
              <p>增值税专用发票一键开具，业财税一体化智能报销</p>
            </div>
          </div>
          <div className={styles.benefitBox}>
            <div className={styles.bIcon}>💳</div>
            <div className={styles.bText}>
              <h5>企业金采账期</h5>
              <p>先采购后付款，最高 500 万授信，最长 90 天对公免息</p>
            </div>
          </div>
          <div className={styles.benefitBox}>
            <div className={styles.bIcon}>🚚</div>
            <div className={styles.bText}>
              <h5>全国多点直发</h5>
              <p>京东全国物流仓储统配，支持一单分发全国各分支机构</p>
            </div>
          </div>
          <div className={styles.benefitBox}>
            <div className={styles.bIcon}>🤝</div>
            <div className={styles.bText}>
              <h5>1对1 专属经理</h5>
              <p>全国 200+ 城市本地化服务团队，上门勘测与方案定制</p>
            </div>
          </div>
        </div>

        {/* 场景采购展厅 */}
        <section className={`w ${styles.sceneSection}`}>
          <div className={styles.sceneHead}>
            <div className={styles.sceneTitleGroup}>
              <h3>🏢 场景化大宗集采 · 阶梯采购专区</h3>
              <p>按采购量直连厂商直发阶梯价，采购量越大单价越低</p>
            </div>
            <Link to="/list" className={styles.sceneMoreLink}>
              查看全部集采品类 ›
            </Link>
          </div>

          <div className={styles.b2bGoodsGrid}>
            {B2B_PRODUCTS.map((prod) => (
              <div key={prod.id} className={styles.b2bItemCard}>
                <div className={styles.b2bThumbBox}>
                  <img src={prod.image} alt={prod.title} />
                  <span className={styles.b2bBatchTag}>{prod.tag}</span>
                </div>
                <div className={styles.b2bInfoBody}>
                  <Link
                    to="/item/$id"
                    params={{ id: '1' }}
                    className={styles.b2bItemTitle}
                    title={prod.title}
                  >
                    {prod.title}
                  </Link>

                  <div className={styles.ladderPriceBox}>
                    {prod.tiers.map((t) => (
                      <div
                        key={t.count}
                        className={`${styles.ladderRow} ${t.isBottom ? styles.highlight : ''}`}
                      >
                        <span>{t.count}</span>
                        <span>{t.price}</span>
                      </div>
                    ))}
                  </div>

                  <div className={styles.b2bActionRow}>
                    <div className={styles.b2bUnitPrice}>
                      {prod.lowestPrice}
                      <small>底价起</small>
                    </div>
                    <button
                      type="button"
                      onClick={() => handleOrder(prod)}
                      className={styles.btnBatchOrder}
                    >
                      大宗起订
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>
    </div>
  )
}
