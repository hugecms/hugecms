import React from 'react'
import { createFileRoute } from '@tanstack/react-router'
import {
  HomeHero,
  HomeSeckill,
  HomeFeaturedMatrix,
  HomeFloor,
  HomeFeed,
  HomeLiftNav,
} from '../../features/mall/components'

export const Route = createFileRoute('/_mall/')({
  component: JDHomePage,
})

function JDHomePage() {
  return (
    <div className="home-exact-body min-h-screen bg-[#f4f4f4]">
      {/* 1. 首屏核心区域：14类目目录 + 轮播 + 资讯便民网格 */}
      <HomeHero />

      {/* 2. 京东秒杀动态专区 */}
      <HomeSeckill />

      {/* 3. 发现好货 & 特色矩阵专区 (特价/榜单/新品/领券) */}
      <HomeFeaturedMatrix />

      {/* 4. 3C数码家电楼层展板 */}
      <HomeFloor />

      {/* 5. 为你推荐无限瀑布流 */}
      <HomeFeed />

      {/* 6. 右侧电梯导航 */}
      <HomeLiftNav />
    </div>
  )
}

export default JDHomePage
