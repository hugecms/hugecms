import { createFileRoute } from '@tanstack/react-router'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallHeader } from '../features/mall/components/MallHeader'
import { HomeHero } from '../features/mall/components/HomeHero'
import { HomeSeckill } from '../features/mall/components/HomeSeckill'
import { HomeFloor } from '../features/mall/components/HomeFloor'
import { MallFooter } from '../features/mall/components/MallFooter'

export const Route = createFileRoute('/')({
  component: HomePage,
})

const FLOOR_1_PRODUCTS = [
  {
    id: 1,
    title: 'Apple iPhone 16 Pro 256GB 原色钛金属 5G手机',
    price: 7999,
    marketPrice: 8999,
    image: 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400&q=80',
    tags: ['自营', '满减'],
    commentCount: '50万+',
    sku: '原色钛金属 / 256GB',
  },
  {
    id: 5,
    title: '华为 Mate 60 Pro 12GB+512GB 雅川青 卫星通话手机',
    price: 6999,
    marketPrice: 7499,
    image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&q=80',
    tags: ['热卖', '自营'],
    commentCount: '20万+',
    sku: '雅川青 / 512GB',
  },
  {
    id: 3,
    title: 'Apple Watch Ultra 智能运动户外手表 49mm 钛金属',
    price: 4999,
    marketPrice: 6299,
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&q=80',
    tags: ['自营', '24期免息'],
    commentCount: '10万+',
    sku: '49mm钛金属 / 高山回环',
  },
  {
    id: 2,
    title: 'Sony WH-1000XM5 无线降噪头戴耳机 黑色',
    price: 1899,
    marketPrice: 2499,
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80',
    tags: ['自营', '降噪首选'],
    commentCount: '15万+',
    sku: '经典黑色 / 官方标配',
  },
]

const FLOOR_2_PRODUCTS = [
  {
    id: 6,
    title: 'MacBook Pro 14英寸 M3 Max 芯片 36GB+1TB 深空黑',
    price: 19999,
    marketPrice: 21999,
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&q=80',
    tags: ['自营', '生产力神器'],
    commentCount: '5万+',
    sku: 'M3 Max / 36GB / 1TB',
  },
  {
    id: 7,
    title: 'ROG 玩家国度 27英寸 4K 160Hz Mini-LED 电竞显示器',
    price: 4599,
    marketPrice: 5299,
    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400&q=80',
    tags: ['电竞爆款', '自营'],
    commentCount: '8万+',
    sku: '27英寸 / 4K Mini-LED',
  },
  {
    id: 8,
    title: 'Keychron Q1 Pro 无线客制化机械键盘 铝合金热插拔',
    price: 899,
    marketPrice: 1099,
    image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&q=80',
    tags: ['客制化', '自营'],
    commentCount: '3万+',
    sku: '茶轴 / 阳极氧化黑',
  },
  {
    id: 4,
    title: '富士 X-T5 复古微单数码相机套机 (16-80mm镜头)',
    price: 11999,
    marketPrice: 13999,
    image: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=400&q=80',
    tags: ['复古颜值', '缺货补到'],
    commentCount: '2万+',
    sku: '经典银黑配色 / 变焦套机',
  },
]

function HomePage() {
  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        {/* 顶部快捷条 */}
        <MallShortcutNav />

        {/* 主头部与搜索 */}
        <MallHeader showMegaMenu={true} />

        {/* 首屏大栏目导航 + 轮播 + 个人中心 */}
        <HomeHero />

        {/* 京东秒杀楼层 */}
        <HomeSeckill />

        {/* 楼层 1：手机数码 */}
        <HomeFloor
          floorNumber="1F"
          title="手机数码 • 潮流先锋"
          icon="📱"
          subCategories={['5G手机', '折叠屏', '微单单反', '运动智能手表', '蓝牙耳机']}
          bannerImg="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80"
          bannerTitle="旗舰领航 跃级体验"
          bannerSubtitle="iPhone 16 Pro 与 Mate 60 Pro 现货狂欢"
          products={FLOOR_1_PRODUCTS}
        />

        {/* 楼层 2：电脑办公 */}
        <HomeFloor
          floorNumber="2F"
          title="电脑办公 • 极客装备"
          icon="💻"
          subCategories={['轻薄笔记本', '游戏本', '4K显示器', '客制化键盘', '专业显卡']}
          bannerImg="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&q=80"
          bannerTitle="硬核性能 创想无界"
          bannerSubtitle="极客电竞与专业生产力神器大集结"
          products={FLOOR_2_PRODUCTS}
        />
      </div>

      {/* 底部保障与版权信息 */}
      <MallFooter />
    </div>
  )
}
