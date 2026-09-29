import React from 'react'
import { Link } from '@tanstack/react-router'
import { Input, Button } from 'antd'
import { SearchOutlined, ArrowLeftOutlined } from '@ant-design/icons'

interface UserHeaderProps {
  title?: string
}

export const UserHeader: React.FC<UserHeaderProps> = ({ title = '我的京东' }) => {
  return (
    <header className="bg-white border-b border-slate-200">
      <div className="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <div className="flex items-center gap-6">
          <Link to="/" className="flex items-center gap-2 group">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-md shadow-red-200">
              <span className="text-white font-black text-xl">JD</span>
            </div>
            <span className="text-xl font-black text-red-600">京东</span>
          </Link>

          <div className="h-6 w-px bg-slate-200" />

          <h2 className="text-lg font-bold text-slate-800">{title}</h2>

          <Link
            to="/"
            className="text-xs text-slate-500 hover:text-rose-600 flex items-center gap-1 transition"
          >
            <ArrowLeftOutlined className="text-[10px]" /> 返回商城首页
          </Link>
        </div>

        <div className="w-72">
          <Input.Search
            placeholder="商品名称 / 订单号"
            enterButton={<Button type="primary" danger>搜索</Button>}
            size="middle"
          />
        </div>
      </div>
    </header>
  )
}
