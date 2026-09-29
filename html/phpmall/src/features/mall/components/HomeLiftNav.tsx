import React from 'react'

export const HomeLiftNav: React.FC = () => {
  const scrollTo = (id: string) => {
    const el = document.getElementById(id)
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' })
    }
  }

  const backToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  return (
    <aside className="lift-nav" id="liftNav">
      <div className="lift-item" onClick={() => scrollTo('sec-seckill')}>
        <span className="lift-text">京东秒杀</span>
      </div>
      <div className="lift-item" onClick={() => scrollTo('sec-features')}>
        <span className="lift-text">特色推荐</span>
      </div>
      <div className="lift-item" onClick={() => scrollTo('sec-digital')}>
        <span className="lift-text">数码家电</span>
      </div>
      <div className="lift-item" onClick={() => scrollTo('sec-recommend')}>
        <span className="lift-text">为你推荐</span>
      </div>
      <div className="lift-item lift-tool" onClick={() => alert('感谢您的反馈建议！')}>
        <span className="lift-text">反馈建议</span>
      </div>
      <div className="lift-item lift-tool lift-top" onClick={backToTop}>
        <span className="lift-text">▲ 顶部</span>
      </div>
    </aside>
  )
}
