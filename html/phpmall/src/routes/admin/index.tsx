import React, { useState } from 'react'
import { createFileRoute, Link } from '@tanstack/react-router'
import {
  Table,
  Tag,
  Button,
  Statistic,
  Card,
  Modal,
  Badge,
  Input,
  message,
  Popconfirm,
} from 'antd'
import {
  DashboardOutlined,
  ShopOutlined,
  SafetyCertificateOutlined,
  CarOutlined,
  PayCircleOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
  AlertOutlined,
  ReloadOutlined,
  HomeOutlined,
  AppstoreOutlined,
} from '@ant-design/icons'

export const Route = createFileRoute('/admin/')({
  component: SuperAdminDashboard,
})

interface MerchantAudit {
  id: string
  company: string
  legalPerson: string
  category: string
  applyDate: string
  deposit: number
  status: 'pending' | 'approved' | 'rejected'
}

interface CommodityRisk {
  id: string
  goodsName: string
  shopName: string
  riskType: string
  reportCount: number
  status: 'normal' | 'pulled'
}

const INITIAL_AUDITS: MerchantAudit[] = [
  {
    id: 'AUD-2026-001',
    company: '北京极客未来数码科技有限公司',
    legalPerson: '李华',
    category: '手机数码 / 智能设备',
    applyDate: '2026-09-28 16:30',
    deposit: 50000,
    status: 'pending',
  },
  {
    id: 'AUD-2026-002',
    company: '杭州云舒轻奢服饰制造有限公司',
    legalPerson: '陈芳',
    category: '潮流女装 / 饰品箱包',
    applyDate: '2026-09-29 09:15',
    deposit: 30000,
    status: 'pending',
  },
  {
    id: 'AUD-2026-003',
    company: '深圳倍电智能充储新能源商行',
    legalPerson: '周伟',
    category: '汽车配件 / 户外电源',
    applyDate: '2026-09-29 10:02',
    deposit: 30000,
    status: 'pending',
  },
]

const INITIAL_RISKS: CommodityRisk[] = [
  {
    id: 'SKU-9901',
    goodsName: '【原装品质】超导磁吸石墨烯快充移动电源 20000mAh',
    shopName: '华强北优质数码甄选店',
    riskType: '虚假宣传 (石墨烯虚标)',
    reportCount: 18,
    status: 'normal',
  },
  {
    id: 'SKU-9902',
    goodsName: '全网首发超低价 瑞士进口全自动机械手表',
    shopName: '瑞士名表工厂直销仓',
    riskType: '仿冒假劣品牌风险',
    reportCount: 32,
    status: 'normal',
  },
]

function SuperAdminDashboard() {
  const [audits, setAudits] = useState<MerchantAudit[]>(INITIAL_AUDITS)
  const [risks, setRisks] = useState<CommodityRisk[]>(INITIAL_RISKS)
  const [selectedAudit, setSelectedAudit] = useState<MerchantAudit | null>(null)

  const handleApproveAudit = (id: string) => {
    setAudits((prev) =>
      prev.map((item) => (item.id === id ? { ...item, status: 'approved' } : item))
    )
    message.success('已通过该商家入驻审核，系统已自动开通其京麦商家后台权限！')
  }

  const handleRejectAudit = (id: string) => {
    setAudits((prev) =>
      prev.map((item) => (item.id === id ? { ...item, status: 'rejected' } : item))
    )
    message.warning('已驳回该商家申请并发送补充资质通知')
  }

  const handlePullCommodity = (id: string) => {
    setRisks((prev) =>
      prev.map((item) => (item.id === id ? { ...item, status: 'pulled' } : item))
    )
    message.success('已对违规商品执行全网强制下架封禁，并扣除该店铺合规信用分 12 分！')
  }

  const pendingCount = audits.filter((a) => a.status === 'pending').length

  const auditColumns = [
    {
      title: '工单号 / 申请企业',
      dataIndex: 'company',
      key: 'company',
      render: (_: any, record: MerchantAudit) => (
        <div>
          <span className="font-semibold text-slate-800 text-xs block">{record.company}</span>
          <span className="text-[11px] text-slate-400">{record.id}</span>
        </div>
      ),
    },
    {
      title: '法定代表人',
      dataIndex: 'legalPerson',
      key: 'legalPerson',
      width: 100,
    },
    {
      title: '申报主营品类',
      dataIndex: 'category',
      key: 'category',
    },
    {
      title: '申请提交时间',
      dataIndex: 'applyDate',
      key: 'applyDate',
      width: 150,
    },
    {
      title: '已缴质保金',
      dataIndex: 'deposit',
      key: 'deposit',
      render: (val: number) => (
        <span className="font-mono font-bold text-slate-800">¥{val.toLocaleString()}</span>
      ),
    },
    {
      title: '审核状态',
      dataIndex: 'status',
      key: 'status',
      width: 110,
      render: (st: string) => (
        <Tag color={st === 'approved' ? 'success' : st === 'rejected' ? 'error' : 'warning'}>
          {st === 'approved' ? '已准入' : st === 'rejected' ? '已驳回' : '待处理'}
        </Tag>
      ),
    },
    {
      title: '操作审批',
      key: 'actions',
      width: 160,
      render: (_: any, record: MerchantAudit) => (
        record.status === 'pending' ? (
          <div className="flex items-center gap-2">
            <Button
              size="small"
              type="primary"
              onClick={() => handleApproveAudit(record.id)}
              className="text-xs"
            >
              准入通过
            </Button>
            <Button
              size="small"
              danger
              onClick={() => handleRejectAudit(record.id)}
              className="text-xs"
            >
              驳回
            </Button>
          </div>
        ) : (
          <span className="text-xs text-slate-400">已办结</span>
        )
      ),
    },
  ]

  const riskColumns = [
    {
      title: '商品名称 / 编号',
      dataIndex: 'goodsName',
      key: 'goodsName',
      render: (txt: string, r: CommodityRisk) => (
        <div>
          <span className="font-medium text-slate-800 text-xs block">{txt}</span>
          <span className="text-[11px] text-slate-400">{r.id}</span>
        </div>
      ),
    },
    {
      title: '所属商户店铺',
      dataIndex: 'shopName',
      key: 'shopName',
    },
    {
      title: '巡检违规项',
      dataIndex: 'riskType',
      key: 'riskType',
      render: (rt: string) => <Tag color="volcano">{rt}</Tag>,
    },
    {
      title: '用户投诉量',
      dataIndex: 'reportCount',
      key: 'reportCount',
      width: 110,
      render: (num: number) => (
        <span className="text-rose-600 font-bold font-mono">{num} 笔投诉</span>
      ),
    },
    {
      title: '状态 / 处置',
      key: 'status',
      width: 140,
      render: (_: any, r: CommodityRisk) => (
        r.status === 'normal' ? (
          <Popconfirm
            title="确认全网强制下架？"
            description="下架后该商品在商城搜索及详情页将无法访问。"
            onConfirm={() => handlePullCommodity(r.id)}
          >
            <Button size="small" type="primary" danger className="text-xs">
              强制下架封禁
            </Button>
          </Popconfirm>
        ) : (
          <Tag color="default">已下架处罚</Tag>
        )
      ),
    },
  ]

  return (
    <div className="min-h-screen bg-slate-900 text-slate-100 flex">
      {/* 左侧深色侧边栏 */}
      <aside className="w-64 bg-slate-950 border-r border-slate-800 flex flex-col shrink-0">
        {/* Logo */}
        <div className="p-4 border-b border-slate-800 flex items-center gap-3">
          <div className="w-9 h-9 rounded-lg bg-blue-600 text-white font-black flex items-center justify-center text-lg shadow-sm">
            JD
          </div>
          <div>
            <h2 className="font-bold text-sm text-white leading-none">运营总控中台</h2>
            <span className="text-[10px] text-blue-400 font-mono">SUPER ADMIN V3.8</span>
          </div>
        </div>

        {/* 侧边栏导航条目 */}
        <div className="p-3 space-y-6 text-xs overflow-y-auto flex-1">
          <div>
            <div className="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 px-3">
              经营大盘
            </div>
            <ul className="space-y-1">
              <li className="px-3 py-2 bg-blue-600/20 text-blue-400 font-bold rounded-lg flex items-center gap-2">
                <DashboardOutlined /> <span>全网经营监控</span>
              </li>
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center gap-2 cursor-pointer transition">
                <AppstoreOutlined /> <span>实时 GMV 大屏</span>
              </li>
            </ul>
          </div>

          <div>
            <div className="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 px-3">
              商户与入驻
            </div>
            <ul className="space-y-1">
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center justify-between cursor-pointer transition">
                <span className="flex items-center gap-2">
                  <ShopOutlined /> <span>入驻资质审核</span>
                </span>
                <span className="px-1.5 py-0.2 bg-rose-600 text-white font-bold text-[10px] rounded-full">
                  {pendingCount}
                </span>
              </li>
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center gap-2 cursor-pointer transition">
                <SafetyCertificateOutlined /> <span>在册商户名录 (42,850)</span>
              </li>
            </ul>
          </div>

          <div>
            <div className="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 px-3">
              履约风控与财务
            </div>
            <ul className="space-y-1">
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center gap-2 cursor-pointer transition">
                <CarOutlined /> <span>京东物流全网干线</span>
              </li>
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center gap-2 cursor-pointer transition">
                <AlertOutlined /> <span>羊毛党与刷单风控</span>
              </li>
              <li className="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg flex items-center gap-2 cursor-pointer transition">
                <PayCircleOutlined /> <span>平台佣金与分账对账</span>
              </li>
            </ul>
          </div>
        </div>
      </aside>

      {/* 右侧主工作台 */}
      <div className="flex-1 flex flex-col min-w-0">
        {/* 顶部 Header */}
        <header className="h-14 bg-slate-950 border-b border-slate-800 px-6 flex items-center justify-between shrink-0">
          <div className="flex items-center gap-3 text-xs">
            <span className="px-2 py-0.5 rounded-full bg-emerald-950 text-emerald-400 font-semibold border border-emerald-800">
              ● 生产主节点集群
            </span>
            <span className="text-slate-400">华北数据中心 (北京亦庄机房) | 响应延时 14ms</span>
          </div>

          <div className="flex items-center gap-6 text-xs text-slate-300">
            <Link to="/" className="hover:text-blue-400 flex items-center gap-1">
              <HomeOutlined /> 京东商城主站
            </Link>
            <Link to="/seller" className="hover:text-blue-400">
              京麦商家后台
            </Link>
            <Link to="/b2b" className="hover:text-blue-400">
              企业购
            </Link>
            <div className="flex items-center gap-2 pl-4 border-l border-slate-800">
              <span className="w-7 h-7 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">
                超
              </span>
              <span className="font-semibold text-white">Admin (运营总控)</span>
            </div>
          </div>
        </header>

        {/* 主体滚动区 */}
        <main className="flex-1 p-6 space-y-6 overflow-y-auto">
          {/* 经营监控四大核心指标 */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <Card className="bg-slate-800/90 border-slate-700 text-white shadow-md">
              <Statistic
                title={<span className="text-slate-400 text-xs font-semibold">今日全网支付流水 (GMV)</span>}
                value={128490200}
                precision={2}
                prefix="¥"
                valueStyle={{ color: '#38bdf8', fontWeight: 800 }}
              />
              <div className="mt-2 text-xs text-slate-400 flex justify-between">
                <span>较昨日同期增长</span>
                <span className="text-emerald-400 font-bold">+8.4% ↑</span>
              </div>
            </Card>

            <Card className="bg-slate-800/90 border-slate-700 text-white shadow-md">
              <Statistic
                title={<span className="text-slate-400 text-xs font-semibold">全网有效订单总量</span>}
                value={385210}
                suffix="单"
                valueStyle={{ color: '#f43f5e', fontWeight: 800 }}
              />
              <div className="mt-2 text-xs text-slate-400 flex justify-between">
                <span>自营仓配履约率</span>
                <span className="text-rose-400 font-bold">76.8%</span>
              </div>
            </Card>

            <Card className="bg-slate-800/90 border-slate-700 text-white shadow-md">
              <Statistic
                title={<span className="text-slate-400 text-xs font-semibold">入驻在册合作商家</span>}
                value={42850}
                suffix="家"
                valueStyle={{ color: '#34d399', fontWeight: 800 }}
              />
              <div className="mt-2 text-xs text-slate-400 flex justify-between">
                <span>今日新入驻</span>
                <span className="text-emerald-400 font-bold">+18 家</span>
              </div>
            </Card>

            <Card className="bg-slate-800/90 border-slate-700 text-white shadow-md">
              <Statistic
                title={<span className="text-slate-400 text-xs font-semibold">平台预估佣金收益</span>}
                value={6424510}
                precision={2}
                prefix="¥"
                valueStyle={{ color: '#fbbf24', fontWeight: 800 }}
              />
              <div className="mt-2 text-xs text-slate-400 flex justify-between">
                <span>质保金监管池</span>
                <span className="text-amber-400 font-bold">¥8.5 亿</span>
              </div>
            </Card>
          </div>

          {/* 重点板块 1：商家入驻资质审核工单池 */}
          <div className="bg-slate-800/90 border border-slate-700 rounded-xl p-5 shadow-md">
            <div className="flex items-center justify-between pb-4 border-b border-slate-700 mb-4">
              <div className="flex items-center gap-2">
                <ShopOutlined className="text-blue-400 text-lg" />
                <h3 className="font-bold text-sm text-white">
                  商户入驻申请与资质审核工单池
                </h3>
                <Tag color="red">{pendingCount} 笔待处理</Tag>
              </div>

              <div className="flex items-center gap-2">
                <Button
                  size="small"
                  type="primary"
                  onClick={() => {
                    setAudits((prev) => prev.map((a) => ({ ...a, status: 'approved' })))
                    message.success('已一键批量批准全部待审商家入驻！')
                  }}
                  className="bg-blue-600 text-xs font-semibold"
                >
                  一键全部准入
                </Button>
                <Button
                  size="small"
                  icon={<ReloadOutlined />}
                  onClick={() => message.info('工单列表已刷新')}
                  className="text-xs"
                >
                  刷新
                </Button>
              </div>
            </div>

            <Table
              dataSource={audits}
              columns={auditColumns}
              rowKey="id"
              pagination={false}
              size="middle"
            />
          </div>

          {/* 重点板块 2：全网商品违规合规巡检 */}
          <div className="bg-slate-800/90 border border-slate-700 rounded-xl p-5 shadow-md">
            <div className="flex items-center justify-between pb-4 border-b border-slate-700 mb-4">
              <div className="flex items-center gap-2">
                <AlertOutlined className="text-rose-500 text-lg" />
                <h3 className="font-bold text-sm text-white">商品合规巡检与违规下架处置</h3>
              </div>
              <span className="text-xs text-slate-400">AI 智能比对 + 用户投诉双重触发</span>
            </div>

            <Table
              dataSource={risks}
              columns={riskColumns}
              rowKey="id"
              pagination={false}
              size="middle"
            />
          </div>
        </main>
      </div>
    </div>
  )
}
