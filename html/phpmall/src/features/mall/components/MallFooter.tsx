import React from 'react'

export const MallFooter: React.FC = () => {
  return (
    <footer className="mt-12 border-t border-[#e5e7eb] bg-white py-8 text-center text-xs text-[#999]">
      <div className="mx-auto max-w-[1200px] space-y-3 px-4">
        <div className="flex justify-center gap-4 text-[#666]">
          <a href="/" className="hover:text-[#e1251b]">
            京东首页
          </a>
          <span>|</span>
          <a href="/cart" className="hover:text-[#e1251b]">
            购物车
          </a>
          <span>|</span>
          <a href="/checkout" className="hover:text-[#e1251b]">
            结算中心
          </a>
          <span>|</span>
          <a href="/seller" className="hover:text-[#1677ff]">
            商家入驻与管理
          </a>
        </div>
        <p className="text-[#999]">
          Copyright © 2004 - 2026 京东JD.com 版权所有 | 消费者维权热线：950618
        </p>
      </div>
    </footer>
  )
}
