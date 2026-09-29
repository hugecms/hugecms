import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import {
  MallShortcutNav,
  MallHeader,
  HomeHero,
  HomeSeckill,
  HomeFeaturedMatrix,
  HomeFloor,
  HomeFeed,
  HomeLiftNav,
  MallFooter,
} from '../features/mall/components'

export const Route = createFileRoute('/')({
  component: JDHomePage,
})

function JDHomePage() {
  return (
    <div className="home-exact-body min-h-screen bg-[#f4f4f4]">
      {/* 1. 顶部快捷导航条 */}
      <MallShortcutNav />

      {/* 2. 头部搜索、Logo 与频道条 */}
      <MallHeader />

      {/* 3. 首屏核心区域：14类目目录 + 轮播 + 资讯便民网格 */}
      <HomeHero />

      {/* 4. 京东秒杀动态专区 */}
      <HomeSeckill />

      {/* 5. 发现好货 & 特色矩阵专区 (特价/榜单/新品/领券) */}
      <HomeFeaturedMatrix />

      {/* 6. 3C数码家电楼层展板 */}
      <HomeFloor />

      {/* 7. 为你推荐无限瀑布流 */}
      <HomeFeed />

      {/* 8. 右侧电梯导航 */}
      <HomeLiftNav />

      {/* 9. 多快好省经典页脚 */}
      <MallFooter />
    </div>
  )
}

export default JDHomePage
