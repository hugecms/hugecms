import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Modal, Form, Input, Select, message } from 'antd'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'
import styles from './merchant.settle.module.css'

export const Route = createFileRoute('/merchant/settle')({
  component: MerchantSettlePage,
})

function MerchantSettlePage() {
  const [isApplyModalOpen, setIsApplyModalOpen] = useState(false)
  const [form] = Form.useForm()

  const handleFinish = (values: any) => {
    message.success(
      `入驻申请已成功提交！工单号：AUD-${Date.now().toString().slice(-6)}。招商经理将在 2 小时内核验 ${values.company} 资质。`
    )
    setIsApplyModalOpen(false)
    form.resetFields()
  }

  return (
    <div className={styles.settleBody}>
      <div>
        <MallShortcutNav />

        {/* 招商专属 Header */}
        <header className={styles.settleHeader}>
          <div className={`w ${styles.settleHeaderInner}`}>
            <div className={styles.logoSettleTitle}>
              <Link to="/" className={styles.logoBox}>
                <span className={styles.logoText}>JD</span>
                <span className={styles.logoSub}>招商</span>
              </Link>
              <h2 className={styles.settleTitle}>开放平台招商入驻</h2>
            </div>
            <nav className={styles.settleNav}>
              <a href="#settle-process" className={styles.navLink}>入驻流程</a>
              <a href="#store-types" className={styles.navLink}>店铺类型</a>
              <a href="#category-fees" className={styles.navLink}>资费标准</a>
              <a href="#faq" className={styles.navLink}>常见问题</a>
              <button
                type="button"
                onClick={() => setIsApplyModalOpen(true)}
                className={styles.btnStartSettle}
              >
                立即入驻开店
              </button>
            </nav>
          </div>
        </header>

        {/* 招商巨幕 Banner */}
        <section className={styles.settleHeroBanner}>
          <div className={`w ${styles.settleHeroContent}`}>
            <div className={styles.heroLeft}>
              <span className={styles.heroSubTag}>京东开放平台 · 官方招商专区</span>
              <h1>聚势同行 · 携手共赢新增长</h1>
              <p className={styles.heroDesc}>
                共享京东 5 亿+ 高品质活跃用户，享首年免平台使用费、全链路自营级供应链赋能与大促超级流量扶持！
              </p>
              <div className={styles.heroHighlights}>
                <div className={styles.hlItem}>
                  <strong>0元</strong>
                  <span>极速开店起步</span>
                </div>
                <div className={styles.hlItem}>
                  <strong>2小时</strong>
                  <span>极速资质初审</span>
                </div>
                <div className={styles.hlItem}>
                  <strong>百亿</strong>
                  <span>全域流量补贴</span>
                </div>
              </div>
              <button
                type="button"
                onClick={() => setIsApplyModalOpen(true)}
                className={styles.btnHeroSettle}
              >
                立即提交入驻申请 ›
              </button>
            </div>
            <div className={styles.heroRight}>
              <img
                src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&q=80"
                alt="现代化企业总部"
                className={styles.heroBannerImg}
              />
            </div>
          </div>
        </section>

        {/* 入驻流程四部曲 */}
        <section className={`w ${styles.settleProcessSection}`} id="settle-process">
          <div className={styles.secTitleBox}>
            <h3>入驻流程只需 4 步</h3>
            <p className={styles.subtitle}>全程线上化透明流转，专职商务顾问 1 对 1 跟踪服务</p>
          </div>

          <div className={styles.processStepsGrid}>
            <div className={styles.stepCard}>
              <div className={styles.stepIcon}>1</div>
              <h4>提交资质材料</h4>
              <p>线上录入企业法人营业执照、商标注册证或品牌授权书，完善开店意向。</p>
            </div>
            <div className={styles.stepArrow}>&gt;</div>
            <div className={styles.stepCard}>
              <div className={styles.stepIcon}>2</div>
              <h4>平台官方审核</h4>
              <p>京东招商经理在 2 个工作日内完成真实性核验，短信与邮件同步进度。</p>
            </div>
            <div className={styles.stepArrow}>&gt;</div>
            <div className={styles.stepCard}>
              <div className={styles.stepIcon}>3</div>
              <h4>完善店铺信息</h4>
              <p>录入店铺招牌、绑定结算对公账户，确认平台商家合作电子协议。</p>
            </div>
            <div className={styles.stepArrow}>&gt;</div>
            <div className={styles.stepCard}>
              <div className={styles.stepIcon}>4</div>
              <h4>商品上架开售</h4>
              <p>登录京麦商家中心后台，批量铺货、配置营销活动，极速出单履约。</p>
            </div>
          </div>
        </section>

        {/* 开放合作店铺类型 */}
        <section className="w" id="store-types">
          <div className={styles.secTitleBox}>
            <h3>多样化店铺形态 · 匹配不同经营场景</h3>
            <p className={styles.subtitle}>无论您是自主品牌制造工厂、渠道总代还是品牌直营均可入驻</p>
          </div>

          <div className={styles.storeTypesGrid}>
            <div className={styles.typeCard}>
              <div className={styles.typeHeader}>
                <span className={styles.typeIcon}>🏛️</span>
                <h4>旗舰店</h4>
              </div>
              <p className={styles.typeDesc}>自有品牌（R标）或独家一级品牌授权商，打造品牌心智阵地。</p>
              <ul className={styles.typeFeatures}>
                <li>• 专属品牌身份认证徽标</li>
                <li>• 开放店铺高级装修大牌模板</li>
                <li>• 优先入选京东自营供应链备选库</li>
              </ul>
            </div>

            <div className={styles.typeCard}>
              <div className={styles.typeHeader}>
                <span className={styles.typeIcon}>🏬</span>
                <h4>专卖店</h4>
              </div>
              <p className={styles.typeDesc}>持他人品牌授权文件在平台开设的店铺，主营单一授权品牌。</p>
              <ul className={styles.typeFeatures}>
                <li>• 官方正品链路信誉背书</li>
                <li>• 精准品类垂直会场置顶展示</li>
                <li>• 共享区域自提仓配联运网络</li>
              </ul>
            </div>

            <div className={styles.typeCard}>
              <div className={styles.typeHeader}>
                <span className={styles.typeIcon}>🛒</span>
                <h4>专营店</h4>
              </div>
              <p className={styles.typeDesc}>经营两个及以上品牌商品的店铺，多品类综合聚合选品运营。</p>
              <ul className={styles.typeFeatures}>
                <li>• 多品类混合经营授权支持</li>
                <li>• 平台百亿补贴频道专属选品席位</li>
                <li>• 供应链批量代发履约无缝适配</li>
              </ul>
            </div>

            <div className={styles.typeCard}>
              <div className={styles.typeHeader}>
                <span className={styles.typeIcon}>🏭</span>
                <h4>工厂店 / 产业带</h4>
              </div>
              <p className={styles.typeDesc}>源头产业带实体生产制造工厂直销，极致性价比源头好物。</p>
              <ul className={styles.typeFeatures}>
                <li>• 京东「厂货通」专属免佣通道</li>
                <li>• C2M 数字化反向定制大数据支撑</li>
                <li>• 极速回款账期直通车</li>
              </ul>
            </div>
          </div>
        </section>

        {/* 资费明细标准 */}
        <section className="w" id="category-fees">
          <div className={styles.secTitleBox}>
            <h3>主要类目资费标准公示</h3>
            <p className={styles.subtitle}>平台费率公开透明，政策扶持期免收首年技术服务年费</p>
          </div>

          <div className={styles.feesTableWrap}>
            <table className={styles.feesTable}>
              <thead>
                <tr>
                  <th>一级主营类目</th>
                  <th>保证金（元）</th>
                  <th>平台扣点费率（扣点）</th>
                  <th>平台年费政策</th>
                  <th>审核周期</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>手机 / 数码 / 电脑办公</strong></td>
                  <td>¥30,000 ~ ¥50,000</td>
                  <td>1.5% ~ 3.0%</td>
                  <td>2026年度免收技术年费</td>
                  <td>1~2 工作日</td>
                </tr>
                <tr>
                  <td><strong>家用电器 / 影音影院</strong></td>
                  <td>¥20,000 ~ ¥40,000</td>
                  <td>2.0% ~ 4.0%</td>
                  <td>2026年度免收技术年费</td>
                  <td>1~2 工作日</td>
                </tr>
                <tr>
                  <td><strong>服饰鞋包 / 美妆个护</strong></td>
                  <td>¥10,000 ~ ¥20,000</td>
                  <td>4.0% ~ 6.0%</td>
                  <td>达成GMV全额返还年费</td>
                  <td>1 工作日极速审</td>
                </tr>
                <tr>
                  <td><strong>食品生鲜 / 酒水母婴</strong></td>
                  <td>¥15,000 ~ ¥30,000</td>
                  <td>3.0% ~ 5.0%</td>
                  <td>入驻前3个月免扣点抽佣</td>
                  <td>1~2 工作日</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        {/* 常见问题 FAQ */}
        <section className="w" id="faq">
          <div className={styles.secTitleBox}>
            <h3>常见入驻咨询答疑</h3>
            <p className={styles.subtitle}>解答开店初期的常见问题与资质规范</p>
          </div>

          <div className={styles.faqGrid}>
            <div className={styles.faqItem}>
              <h4>Q: 个人个体工商户可以入驻京东吗？</h4>
              <p>答：可以！京东开放平台支持企业法人（有限责任公司/股份公司）及个体工商户入驻。提供统一社会信用代码营业执照即可申请。</p>
            </div>
            <div className={styles.faqItem}>
              <h4>Q: 提交入驻申请后多久能有审核结果？</h4>
              <p>答：初审通常在 2 个工作日内完成，审核进度将通过注册手机短信及系统后台即时同步提示，若需补充材料将有招商专员协助。</p>
            </div>
            <div className={styles.faqItem}>
              <h4>Q: 商家必须使用京东物流仓配吗？</h4>
              <p>答：不强制。商家可自主选择第三方品牌快递（如顺丰、圆通、中通等）履约配送，亦可自由接入京东物流享「京东配送」时效标。</p>
            </div>
            <div className={styles.faqItem}>
              <h4>Q: 货款结算周期与提现规则是怎样的？</h4>
              <p>答：买家确认收货或系统自动完结后，款项即时入账商家对公钱包，支持随时按需发起提现，最快 T+1 到账绑定的对公结算账户。</p>
            </div>
          </div>
        </section>
      </div>

      {/* 弹窗：立即申请开店 Modal */}
      <Modal
        title="商家入驻意向提报"
        open={isApplyModalOpen}
        onCancel={() => setIsApplyModalOpen(false)}
        footer={null}
        destroyOnClose
        width={560}
      >
        <Form form={form} layout="vertical" onFinish={handleFinish} className="pt-2">
          <Form.Item name="company" label="企业/个体工商户名称" rules={[{ required: true, message: '请输入营业执照上的主体全称' }]}>
            <Input placeholder="例如：北京顺通商贸有限公司" />
          </Form.Item>

          <div className="grid grid-cols-2 gap-4">
            <Form.Item name="category" label="主营意向类目" initialValue="数码电脑" rules={[{ required: true }]}>
              <Select options={[
                { value: '数码电脑', label: '数码 / 电脑办公' },
                { value: '家用电器', label: '家用电器 / 影音' },
                { value: '服饰鞋包', label: '服饰鞋包 / 运动户外' },
                { value: '美妆个护', label: '美妆个护 / 居家日用' },
                { value: '食品生鲜', label: '食品生鲜 / 农特产' },
              ]} />
            </Form.Item>

            <Form.Item name="storeType" label="申请店铺类型" initialValue="旗舰店" rules={[{ required: true }]}>
              <Select options={[
                { value: '旗舰店', label: '旗舰店（自有品牌）' },
                { value: '专卖店', label: '专卖店（单一授权）' },
                { value: '专营店', label: '专营店（多品牌）' },
                { value: '工厂店', label: '产业带工厂直营店' },
              ]} />
            </Form.Item>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <Form.Item name="contactName" label="联系人姓名" rules={[{ required: true, message: '请输入对接人姓名' }]}>
              <Input placeholder="业务负责人姓名" />
            </Form.Item>

            <Form.Item name="contactPhone" label="联系手机号" rules={[{ required: true, message: '请输入接收审核结果的手机号' }]}>
              <Input placeholder="11位手机号码" />
            </Form.Item>
          </div>

          <Form.Item name="brandName" label="经营品牌名称">
            <Input placeholder="如：Apple / 华为 / 自有商标名" />
          </Form.Item>

          <Button type="primary" danger block size="large" htmlType="submit" className="mt-2 bg-[#e1251b] font-bold">
            提交入驻申请 · 2小时内审核回电
          </Button>
        </Form>
      </Modal>

      <MallFooter />
    </div>
  )
}
