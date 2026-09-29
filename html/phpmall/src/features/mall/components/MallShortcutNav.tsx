import React from 'react'
import { EnvironmentOutlined } from '@ant-design/icons'

export const MallShortcutNav: React.FC = () => {
  return (
    <div className="h-8 border-b border-[#f0f0f0] bg-[#e3e4e5] text-xs text-[#999]">
      <div className="mx-auto flex h-full max-w-[1200px] items-center justify-between px-4">
        <div className="flex items-center gap-1 text-[#666]">
          <EnvironmentOutlined className="text-[#e1251b]" />
          <span>北京</span>
        </div>

        <ul className="flex items-center gap-4 text-[#666]">
          <li>
            <a href="/login" className="text-[#e1251b] hover:underline">
              你好，请登录
            </a>
            <span className="mx-1.5 text-[#ccc]">|</span>
            <a href="/register" className="text-[#e1251b] hover:underline">
              免费注册
            </a>
          </li>
          <li className="text-[#ccc]">|</li>
          <li>
            <a href="/user/orders" className="hover:text-[#e1251b]">
              我的订单
            </a>
          </li>
          <li className="text-[#ccc]">|</li>
          <li>
            <a href="/seller" className="text-[#1677ff] hover:underline">
              商家工作台
            </a>
          </li>
          <li className="text-[#ccc]">|</li>
          <li>
            <a href="/" className="hover:text-[#e1251b]">
              返回首页
            </a>
          </li>
        </ul>
      </div>
    </div>
  )
}
