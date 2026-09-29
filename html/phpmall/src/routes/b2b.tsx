import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import { Form, Input, Select, Button, Tag, Card, message } from 'antd'
import {
  BankOutlined,
  CheckCircleOutlined,
  AuditOutlined,
  SafetyCertificateOutlined,
  ThunderboltFilled,
  HomeOutlined,
} from '@ant-design/icons'
import { MallShortcutNav } from '../features/mall/components/MallShortcutNav'
import { MallFooter } from '../features/mall/components/MallFooter'

export const Route = createFileRoute('/b2b')({
  component: B2BProcurementPage,
})

const B2B_PRODUCTS = [
  {
    id: 101,
    title: 'ThinkPad T14p 高性能商务轻薄本 (i7/32G/1TB)',
    retailPrice: 8999,
    b2bTiers: [
      { min: 5, price: 8299 },
      { min: 20, price: 7799 },
      { min: 50, price: 7299 },
    ],
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&q=80',
    tag: '办公首选',
  },
  {
    id: 102,
    title: 'MAXHUB 75英寸 4K 智能会议平板一体机 (无线投屏+白板)',
    retailPrice: 15999,
    b2bTiers: [
      { min: 2, price: 14200 },
      { min: 5, price: 13500 },
      { min: 10, price: 12800 },
    ],
    image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&q=80',
    tag: '会议智能',
  },
  {
    id: 103,
    title: '京东E卡 经典电子卡 500元面值 (员工中秋/年节福利)',
    retailPrice: 500,
    b2bTiers: [
      { min: 50, price: 492 },
      { min: 200, price: 485 },
      { min: 500, price: 475 },
    ],
    image: 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=300&q=80',
    tag: '员工福利',
  },
]

function B2BProcurementPage() {
  const [form] = Form.useForm()

  const handleFinish = (values: any) => {
    message.success(
      `感谢您提报采购意向！我们已指派资深客户经理对接 ${values.company}，将在 2 小时内致电联系。`
    )
    form.resetFields()
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-between">
      <div>
        <MallShortcutNav />

        {/* 企业购专享 Header */}
        <header className="bg-slate-900 text-white border-b border-slate-800">
          <div className="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div className="flex items-center gap-4">
              <Link to="/" className="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-black text-xl shadow">
                JD
              </Link>
              <div>
                <h1 className="text-xl font-black text-white tracking-wide">京东企业购</h1>
                <p className="text-[10px] text-blue-300 font-mono tracking-wider">
                  ENTERPRISE PROCUREMENT · 数字化大宗集采解决方案
                </p>
              </div>
            </div>

            <div className="flex items-center gap-6 text-xs text-slate-300">
              <Link to="/" className="hover:text-white flex items-center gap-1">
                <HomeOutlined /> 返回个人商城
              </Link>
              <Link to="/seller" className="hover:text-white">
                商家入驻
              </Link>
              <span className="text-blue-400 font-bold">客服专线: 400-606-5500</span>
            </div>
          </div>
        </header>

        {/* Hero Banner 与 快捷询价卡片 */}
        <section className="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white py-12 border-b border-blue-900">
          <div className="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            {/* 左侧标语与指标 */}
            <div className="lg:col-span-7 space-y-4">
              <Tag color="blue" className="text-xs px-2.5 py-0.5 font-bold">
                2026年度企业数字化采购扶持专项
              </Tag>
              <h2 className="text-3xl font-black tracking-tight leading-tight">
                全品类大宗集采 • 直享阶梯出厂底价
              </h2>
              <p className="text-xs text-blue-200 leading-relaxed max-w-xl">
                为中小企业、集团客户及事业单位打造。正规 13% 增值税专票一键开具、支持 30-90 天对公免息账期，京东全国八大总仓一体化智能配送上门。
              </p>

              <div className="grid grid-cols-3 gap-4 pt-4 border-t border-blue-800/80">
                <div>
                  <div className="text-2xl font-black text-blue-400">800万+</div>
                  <div className="text-xs text-slate-300 mt-0.5">认证注册企业</div>
                </div>
                <div>
                  <div className="text-2xl font-black text-blue-400">15%~35%</div>
                  <div className="text-xs text-slate-300 mt-0.5">平均采购降本</div>
                </div>
                <div>
                  <div className="text-2xl font-black text-blue-400">1对1</div>
                  <div className="text-xs text-slate-300 mt-0.5">资深客户经理履约</div>
                </div>
              </div>
            </div>

            {/* 右侧询价表单 */}
            <div className="lg:col-span-5 bg-white text-slate-800 rounded-2xl p-6 shadow-2xl border border-slate-100">
              <div className="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 className="font-bold text-sm text-slate-800 flex items-center gap-1.5">
                  <AuditOutlined className="text-blue-600" /> 企业大宗采购极速询价
                </h3>
                <span className="text-[11px] text-emerald-600 font-semibold">2小时内专人回电</span>
              </div>

              <Form form={form} layout="vertical" onFinish={handleFinish} size="middle">
                <Form.Item
                  label={<span className="text-xs font-semibold">企业名称</span>}
                  name="company"
                  rules={[{ required: true, message: '请输入企业名称' }]}
                >
                  <Input placeholder="如：北京科技有限公司" className="text-xs" />
                </Form.Item>

                <Form.Item
                  label={<span className="text-xs font-semibold">意向采购品类</span>}
                  name="category"
                  initialValue="办公电脑"
                  rules={[{ required: true }]}
                >
                  <Select
                    options={[
                      { label: '商用办公电脑与会议平板', value: '办公电脑' },
                      { label: '员工福利与京东E卡', value: '员工福利' },
                      { label: '工业品 MRO 与劳保五金', value: '工业品' },
                    ]}
                  />
                </Form.Item>

                <Form.Item
                  label={<span className="text-xs font-semibold">联系电话</span>}
                  name="phone"
                  rules={[{ required: true, message: '请输入联系电话' }]}
                >
                  <Input placeholder="我们将严格保密您的联系信息" className="text-xs" />
                </Form.Item>

                <Button type="primary" htmlType="submit" block className="bg-blue-600 font-bold h-10 mt-2">
                  提交大宗集采需求
                </Button>
              </Form>
            </div>
          </div>
        </section>

        {/* 阶梯报价热采商品 */}
        <section className="max-w-7xl mx-auto px-4 py-8">
          <div className="flex items-center justify-between pb-3 border-b-2 border-blue-900 mb-6">
            <h3 className="text-lg font-black text-slate-800 flex items-center gap-2">
              <BankOutlined className="text-blue-600" /> 阶梯批量集采专区
            </h3>
            <span className="text-xs text-slate-500">数量越多 单价越优</span>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {B2B_PRODUCTS.map((p) => (
              <div
                key={p.id}
                className="bg-white rounded-xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between hover:shadow-md transition"
              >
                <div>
                  <div className="aspect-video bg-slate-50 rounded-lg overflow-hidden mb-3 relative">
                    <img src={p.image} alt={p.title} className="w-full h-full object-cover" />
                    <span className="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                      {p.tag}
                    </span>
                  </div>

                  <h4 className="text-xs font-bold text-slate-800 line-clamp-2 h-8">{p.title}</h4>
                  <div className="text-xs text-slate-400 mt-1">
                    个人零售价：<del>¥{p.retailPrice}</del>
                  </div>

                  {/* 阶梯价格阶梯展示 */}
                  <div className="mt-3 bg-blue-50/60 rounded-lg p-2.5 space-y-1.5 text-xs">
                    <div className="text-[11px] font-bold text-blue-900">企业阶梯专享价：</div>
                    {p.b2bTiers.map((t) => (
                      <div key={t.min} className="flex justify-between items-center text-slate-700">
                        <span>≥ {t.min} 件：</span>
                        <span className="font-bold text-blue-700">¥{t.price} / 件</span>
                      </div>
                    ))}
                  </div>
                </div>

                <div className="mt-4 pt-3 border-t border-slate-100">
                  <Button
                    type="primary"
                    block
                    className="bg-blue-600 hover:bg-blue-700 text-xs font-semibold"
                    onClick={() => message.success(`已加入 ${p.title} 的批量询价清单！`)}
                  >
                    加入批量询价单
                  </Button>
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>

      <MallFooter />
    </div>
  )
}
