import React, { useState } from 'react'
import { createFileRoute, Link, useNavigate } from '@tanstack/react-router'
import {
  Breadcrumb,
  Tag,
  Button,
  InputNumber,
  Radio,
  Tabs,
  Modal,
  Rate,
  Progress,
  message,
} from 'antd'
import {
  ShoppingCartOutlined,
  ThunderboltFilled,
  HeartOutlined,
  ShareAltOutlined,
  CheckCircleFilled,
  SafetyCertificateOutlined,
  CarOutlined,
  CustomerServiceOutlined,
} from '@ant-design/icons'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallHeader } from '../features/mall/components/MallHeader'
import { MallFooter } from '../features/mall/components/MallFooter'
import { useCartStore } from '../stores/cartStore'

export const Route = createFileRoute('/item/$id')({
  component: ProductDetailPage,
})

// 详情商品模拟数据库
const PRODUCT_DETAILS: Record<string, any> = {
  '1': {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 支持移动联通电信5G 双卡双待手机',
    slogan: '【年终钜惠】A18 Pro强悍芯片！4800万超广角微距，4K 120帧杜比视界，钛金属轻盈坚固！以旧换新至高补贴1000元！',
    price: 7999,
    originalPrice: 8999,
    rating: 98,
    commentCount: '50万+',
    code: '100086421992',
    images: [
      'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&q=80',
      'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80',
      'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&q=80',
      'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
    ],
    colors: ['原色钛金属', '白色钛金属', '黑色钛金属', '沙漠色钛金属'],
    versions: [
      { name: '128GB', price: 7199 },
      { name: '256GB', price: 7999 },
      { name: '512GB', price: 9999 },
      { name: '1TB', price: 11999 },
    ],
    services: [
      { name: '2年碎屏无忧保', price: 399 },
      { name: '全方位 AppleCare+ 服务包', price: 1199 },
    ],
    specs: [
      { key: '商品名称', value: 'Apple iPhone 16 Pro' },
      { key: 'CPU型号', value: 'A18 Pro 6核仿生芯片' },
      { key: '机身材质', value: '航空级钛金属边框 + 亚光质感玻璃背板' },
      { key: '屏幕尺寸', value: '6.3英寸 超视网膜 XDR 显示屏' },
      { key: '后置摄像头', value: '4800万像素主摄 + 4800万像素超广角 + 1200万5倍长焦' },
      { key: '充电功率', value: 'MagSafe 25W无线快充 / USB-C 3.0' },
    ],
  },
}

// 兜底默认商品
const DEFAULT_PRODUCT = PRODUCT_DETAILS['1']

function ProductDetailPage() {
  const { id } = Route.useParams()
  const navigate = useNavigate()
  const addItem = useCartStore((state) => state.addItem)

  const product = PRODUCT_DETAILS[id] || {
    ...DEFAULT_PRODUCT,
    id: Number(id) || 1,
    title: `高端数码甄选商品 (编号 #${id}) - 正品行货 品质保障`,
  }

  const [activeImageIndex, setActiveImageIndex] = useState(0)
  const [selectedColor, setSelectedColor] = useState(product.colors[0])
  const [selectedVersion, setSelectedVersion] = useState(product.versions[1])
  const [selectedService, setSelectedService] = useState<string | null>(null)
  const [quantity, setQuantity] = useState(1)
  const [isSuccessModalVisible, setIsSuccessModalVisible] = useState(false)

  // 计算当前最终单价
  const currentPrice =
    selectedVersion.price +
    (selectedService ? product.services.find((s: any) => s.name === selectedService)?.price || 0 : 0)

  // 加入购物车
  const handleAddToCart = () => {
    addItem({
      goodsId: product.id,
      title: product.title,
      price: currentPrice,
      quantity,
      image: product.images[activeImageIndex] || product.images[0],
      sku: `${selectedColor} / ${selectedVersion.name}${selectedService ? ` / ${selectedService}` : ''}`,
      shopName: 'Apple产品京东自营旗舰店',
    })
    setIsSuccessModalVisible(true)
  }

  // 立即购买
  const handleBuyNow = () => {
    addItem({
      goodsId: product.id,
      title: product.title,
      price: currentPrice,
      quantity,
      image: product.images[activeImageIndex] || product.images[0],
      sku: `${selectedColor} / ${selectedVersion.name}${selectedService ? ` / ${selectedService}` : ''}`,
      shopName: 'Apple产品京东自营旗舰店',
    })
    navigate({ to: '/checkout' })
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        <MallShortcutNav />
        <MallHeader />

        <div className="max-w-7xl mx-auto px-4 py-4">
          {/* 面包屑导航 */}
          <div className="pb-4 text-xs">
            <Breadcrumb
              items={[
                { title: <Link to="/">首页</Link> },
                { title: <Link to="/list">手机数码</Link> },
                { title: <Link to="/list" search={{ q: 'Apple' }}>Apple</Link> },
                { title: product.title.slice(0, 24) + '...' },
              ]}
            />
          </div>

          {/* 核心交易与画廊主容器 */}
          <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-6 grid grid-cols-1 md:grid-cols-12 gap-8 mb-6">
            {/* 左侧：画廊相册 */}
            <div className="md:col-span-5 flex flex-col">
              {/* 大图预览 */}
              <div className="aspect-square rounded-xl overflow-hidden bg-slate-50 border border-slate-200 relative group">
                <img
                  src={product.images[activeImageIndex]}
                  alt={product.title}
                  className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                />
                <span className="absolute top-3 left-3 bg-rose-600 text-white text-xs font-bold px-2 py-0.5 rounded shadow">
                  自营次日达
                </span>
              </div>

              {/* 缩略图列表 */}
              <div className="flex gap-2.5 mt-4 overflow-x-auto py-1">
                {product.images.map((img: string, idx: number) => (
                  <button
                    key={idx}
                    type="button"
                    onClick={() => setActiveImageIndex(idx)}
                    className={`w-16 h-16 rounded-lg overflow-hidden border-2 shrink-0 transition cursor-pointer ${
                      activeImageIndex === idx
                        ? 'border-rose-600 shadow-sm'
                        : 'border-slate-200 hover:border-slate-400 opacity-70 hover:opacity-100'
                    }`}
                  >
                    <img src={img} alt="thumb" className="w-full h-full object-cover" />
                  </button>
                ))}
              </div>

              {/* 编号与分享操作 */}
              <div className="flex items-center justify-between text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100">
                <span>商品编号：{product.code}</span>
                <div className="flex items-center gap-3">
                  <button
                    type="button"
                    onClick={() => message.info('已复制商品链接到剪贴板')}
                    className="flex items-center gap-1 hover:text-rose-600 cursor-pointer"
                  >
                    <ShareAltOutlined /> 分享
                  </button>
                  <button
                    type="button"
                    onClick={() => message.success('已关注该商品')}
                    className="flex items-center gap-1 hover:text-rose-600 cursor-pointer"
                  >
                    <HeartOutlined /> 关注 (12.8万)
                  </button>
                </div>
              </div>
            </div>

            {/* 右侧：商品交易信息与 SKU 选择 */}
            <div className="md:col-span-7 flex flex-col justify-between">
              <div>
                {/* 标题与卖点 */}
                <h1 className="text-xl font-bold text-slate-900 leading-snug">
                  <span className="bg-rose-600 text-white text-xs font-bold px-1.5 py-0.5 rounded mr-2 align-middle">
                    京东自营
                  </span>
                  {product.title}
                </h1>
                <p className="text-xs text-rose-600 mt-2 font-medium bg-rose-50/70 p-2.5 rounded-lg border border-rose-100/60">
                  {product.slogan}
                </p>

                {/* 价格与秒杀横幅 */}
                <div className="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-4 my-4 border border-red-100">
                  <div className="flex items-baseline justify-between">
                    <div>
                      <div className="text-xs text-rose-600 font-bold flex items-center gap-1 mb-1">
                        <ThunderboltFilled /> 京东秒杀 • 限时特惠
                      </div>
                      <div className="flex items-baseline gap-2">
                        <span className="text-rose-600 font-bold text-lg">¥</span>
                        <span className="text-3xl font-black text-rose-600 font-mono tracking-tight">
                          {currentPrice.toFixed(2)}
                        </span>
                        <span className="text-xs text-slate-400 line-through">
                          ¥{product.originalPrice.toFixed(2)}
                        </span>
                        <Tag color="error">直降 ¥{product.originalPrice - currentPrice}</Tag>
                      </div>
                    </div>

                    <div className="text-right border-l border-red-200 pl-4">
                      <div className="text-[11px] text-slate-500">累计评价</div>
                      <div className="text-base font-bold text-slate-800">{product.commentCount}</div>
                      <div className="text-[11px] text-rose-600 font-medium">
                        好评率 {product.rating}%
                      </div>
                    </div>
                  </div>

                  {/* 促销活动 */}
                  <div className="mt-3 pt-3 border-t border-red-100 flex items-center gap-2 text-xs">
                    <span className="text-slate-500 shrink-0">促 销：</span>
                    <Tag color="red">跨店满减</Tag>
                    <span className="text-slate-700">每满300元减50元，可跨店凑单</span>
                  </div>
                </div>

                {/* 配送说明 */}
                <div className="text-xs text-slate-600 space-y-2 pb-4 border-b border-slate-100">
                  <div className="flex items-center gap-2">
                    <span className="text-slate-400 w-16 shrink-0">配 送 至：</span>
                    <span className="font-medium text-slate-800 flex items-center gap-1">
                      <CarOutlined className="text-rose-600" /> 北京市朝阳区三环到四环之间
                    </span>
                    <span className="text-emerald-600 font-semibold">【现货】</span>
                    <span className="text-slate-400">23:00前下单，预计明日送达</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <span className="text-slate-400 w-16 shrink-0">增值保障：</span>
                    <span className="text-slate-600 flex items-center gap-1">
                      <SafetyCertificateOutlined className="text-emerald-500" /> 正品保障 •
                      七天无理由退货 • 破损包退 • 免费上门取件
                    </span>
                  </div>
                </div>

                {/* SKU 多维选择 */}
                <div className="py-4 space-y-4 text-xs">
                  {/* 颜色选择 */}
                  <div className="flex items-center">
                    <span className="text-slate-400 w-16 shrink-0">选择颜色：</span>
                    <div className="flex flex-wrap gap-2">
                      {product.colors.map((c: string) => (
                        <button
                          key={c}
                          type="button"
                          onClick={() => setSelectedColor(c)}
                          className={`px-3 py-1.5 rounded-lg border text-xs font-medium transition cursor-pointer ${
                            selectedColor === c
                              ? 'border-rose-600 text-rose-600 bg-rose-50/50 shadow-xs'
                              : 'border-slate-200 text-slate-700 hover:border-slate-300'
                          }`}
                        >
                          {c}
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* 容量版本选择 */}
                  <div className="flex items-center">
                    <span className="text-slate-400 w-16 shrink-0">版本容量：</span>
                    <div className="flex flex-wrap gap-2">
                      {product.versions.map((v: any) => (
                        <button
                          key={v.name}
                          type="button"
                          onClick={() => setSelectedVersion(v)}
                          className={`px-3 py-1.5 rounded-lg border text-xs font-medium transition cursor-pointer ${
                            selectedVersion.name === v.name
                              ? 'border-rose-600 text-rose-600 bg-rose-50/50 shadow-xs'
                              : 'border-slate-200 text-slate-700 hover:border-slate-300'
                          }`}
                        >
                          <span>{v.name}</span>
                          <span className="ml-1 text-[11px] opacity-75">¥{v.price}</span>
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* 增值保障险种 */}
                  <div className="flex items-center">
                    <span className="text-slate-400 w-16 shrink-0">保障服务：</span>
                    <div className="flex flex-wrap gap-2">
                      {product.services.map((s: any) => (
                        <button
                          key={s.name}
                          type="button"
                          onClick={() =>
                            setSelectedService(selectedService === s.name ? null : s.name)
                          }
                          className={`px-3 py-1.5 rounded-lg border text-xs font-medium transition cursor-pointer ${
                            selectedService === s.name
                              ? 'border-rose-600 text-rose-600 bg-rose-50/50 shadow-xs'
                              : 'border-slate-200 text-slate-700 hover:border-slate-300'
                          }`}
                        >
                          <span>{s.name}</span>
                          <span className="ml-1 text-[11px] text-rose-600">+¥{s.price}</span>
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* 购买数量 */}
                  <div className="flex items-center">
                    <span className="text-slate-400 w-16 shrink-0">购买数量：</span>
                    <InputNumber
                      min={1}
                      max={99}
                      value={quantity}
                      onChange={(val) => setQuantity(val || 1)}
                      size="middle"
                    />
                    <span className="text-slate-400 ml-3">件 (库存充足)</span>
                  </div>
                </div>
              </div>

              {/* 核心操作按钮 */}
              <div className="pt-4 border-t border-slate-100 flex items-center gap-4">
                <Button
                  size="large"
                  type="primary"
                  danger
                  ghost
                  icon={<ShoppingCartOutlined />}
                  onClick={handleAddToCart}
                  className="flex-1 h-12 text-sm font-bold shadow-sm"
                >
                  加入购物车
                </Button>
                <Button
                  size="large"
                  type="primary"
                  danger
                  icon={<ThunderboltFilled />}
                  onClick={handleBuyNow}
                  className="flex-1 h-12 text-sm font-bold shadow-md shadow-rose-200"
                >
                  立即购买
                </Button>
              </div>
            </div>
          </div>

          {/* 下方商品详情 Tabs */}
          <div className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-6 mb-8">
            <Tabs
              defaultActiveKey="detail"
              items={[
                {
                  key: 'detail',
                  label: <span className="text-sm font-bold">商品介绍</span>,
                  children: (
                    <div className="space-y-6 py-4">
                      <div className="p-4 bg-slate-50 rounded-lg text-xs text-slate-600 leading-relaxed">
                        <h4 className="font-bold text-slate-800 text-sm mb-2">产品核心技术亮点</h4>
                        <p>
                          全新搭载 A18 Pro 芯片，配备新一代 6 核图形处理器，光线追踪性能提升至高可达 2 倍。
                          支持下一代人像功能、微距摄影与 4800 万像素超广角，让每一拍都生动震撼。
                          采用航空级 5 级钛金属边框，带来超高强度与极其轻盈的舒适手感。
                        </p>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <img
                          src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=800&q=80"
                          alt="海报1"
                          className="rounded-lg object-cover w-full h-80"
                        />
                        <img
                          src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80"
                          alt="海报2"
                          className="rounded-lg object-cover w-full h-80"
                        />
                      </div>
                    </div>
                  ),
                },
                {
                  key: 'specs',
                  label: <span className="text-sm font-bold">规格与包装</span>,
                  children: (
                    <div className="py-4 divide-y divide-slate-100 text-xs">
                      {product.specs.map((spec: any) => (
                        <div key={spec.key} className="py-3 flex">
                          <span className="w-32 text-slate-400 font-medium">{spec.key}</span>
                          <span className="text-slate-800 font-semibold">{spec.value}</span>
                        </div>
                      ))}
                    </div>
                  ),
                },
                {
                  key: 'service',
                  label: <span className="text-sm font-bold">售后保障</span>,
                  children: (
                    <div className="py-4 space-y-4 text-xs text-slate-600">
                      <div className="p-4 border border-emerald-100 bg-emerald-50/50 rounded-lg flex items-start gap-3">
                        <CheckCircleFilled className="text-emerald-600 text-base mt-0.5" />
                        <div>
                          <h4 className="font-bold text-slate-800">京东自营正品行货保证</h4>
                          <p className="mt-1">
                            京东商城向您保证所售商品均为正品行货，京东自营商品开具机打发票或电子发票。
                          </p>
                        </div>
                      </div>
                      <div className="p-4 border border-blue-100 bg-blue-50/50 rounded-lg flex items-start gap-3">
                        <SafetyCertificateOutlined className="text-blue-600 text-base mt-0.5" />
                        <div>
                          <h4 className="font-bold text-slate-800">全国联保与上门取件</h4>
                          <p className="mt-1">
                            凭质保证书及京东商城发票，可享受全国联保服务；如遇故障，京东提供免费上门取退换件。
                          </p>
                        </div>
                      </div>
                    </div>
                  ),
                },
                {
                  key: 'comments',
                  label: (
                    <span className="text-sm font-bold">
                      商品评价 ({product.commentCount})
                    </span>
                  ),
                  children: (
                    <div className="py-4">
                      {/* 好评率总览 */}
                      <div className="p-4 bg-slate-50 rounded-xl flex items-center justify-around mb-6">
                        <div className="text-center">
                          <div className="text-3xl font-black text-rose-600">{product.rating}%</div>
                          <div className="text-xs text-slate-400 mt-1">好评度</div>
                        </div>
                        <div className="w-64 space-y-1 text-xs text-slate-500">
                          <div className="flex items-center gap-2">
                            <span>好评</span>
                            <Progress percent={98} size="small" strokeColor="#e11d48" />
                          </div>
                          <div className="flex items-center gap-2">
                            <span>中评</span>
                            <Progress percent={1.5} size="small" strokeColor="#f59e0b" />
                          </div>
                          <div className="flex items-center gap-2">
                            <span>差评</span>
                            <Progress percent={0.5} size="small" strokeColor="#94a3b8" />
                          </div>
                        </div>
                      </div>

                      {/* 评价列表 */}
                      <div className="space-y-4 divide-y divide-slate-100 text-xs">
                        <div className="pt-4">
                          <div className="flex items-center justify-between mb-2">
                            <span className="font-bold text-slate-800">j***8 (PLUS会员)</span>
                            <Rate disabled defaultValue={5} className="text-xs text-rose-500" />
                          </div>
                          <p className="text-slate-700 leading-relaxed">
                            原色钛金属非常耐看，质感无敌！拿在手里比上一代轻了不少，拍照快门实体键很方便，微距效果惊艳！
                          </p>
                          <div className="text-slate-400 text-[11px] mt-2">
                            2026-09-28 购买版本: 原色钛金属 256GB
                          </div>
                        </div>

                        <div className="pt-4">
                          <div className="flex items-center justify-between mb-2">
                            <span className="font-bold text-slate-800">m***k (金牌会员)</span>
                            <Rate disabled defaultValue={5} className="text-xs text-rose-500" />
                          </div>
                          <p className="text-slate-700 leading-relaxed">
                            京东物流次日一早就送到了，包装严实无磕碰，开箱完美正品，发票和保修都能直接查到，放心！
                          </p>
                          <div className="text-slate-400 text-[11px] mt-2">
                            2026-09-27 购买版本: 沙漠色钛金属 512GB
                          </div>
                        </div>
                      </div>
                    </div>
                  ),
                },
              ]}
            />
          </div>
        </div>
      </div>

      {/* 加购成功弹窗 */}
      <Modal
        title={
          <div className="flex items-center gap-2 text-emerald-600 font-bold">
            <CheckCircleFilled className="text-xl" />
            <span>商品已成功加入购物车！</span>
          </div>
        }
        open={isSuccessModalVisible}
        onCancel={() => setIsSuccessModalVisible(false)}
        footer={[
          <Button key="continue" onClick={() => setIsSuccessModalVisible(false)}>
            继续逛逛
          </Button>,
          <Link key="cart" to="/cart">
            <Button type="primary" danger>
              去购物车结算 &gt;
            </Button>
          </Link>,
        ]}
      >
        <div className="py-4 flex gap-4 items-center">
          <img
            src={product.images[activeImageIndex]}
            alt="product"
            className="w-16 h-16 object-cover rounded-lg border border-slate-200"
          />
          <div>
            <h4 className="text-xs font-bold text-slate-800 line-clamp-1">{product.title}</h4>
            <p className="text-xs text-slate-400 mt-1">
              规格：{selectedColor} / {selectedVersion.name}
            </p>
            <p className="text-xs text-rose-600 font-bold mt-1">
              ¥{currentPrice.toFixed(2)} x {quantity} 件
            </p>
          </div>
        </div>
      </Modal>

      <MallFooter />
    </div>
  )
}
