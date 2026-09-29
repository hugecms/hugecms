import React, { useState, useMemo } from 'react'
import { Menu, Input, Badge, message } from 'antd'
import type { MenuProps } from 'antd'
import {
  DashboardOutlined,
  AlertOutlined,
  AppstoreOutlined,
  CarOutlined,
  SyncOutlined,
  ThunderboltOutlined,
  CustomerServiceOutlined,
  SkinOutlined,
  LineChartOutlined,
  DollarOutlined,
  SettingOutlined,
  SearchOutlined,
  LeftOutlined,
  RightOutlined,
} from '@ant-design/icons'
import { useSellerStore } from '../../../stores/sellerStore'

type MenuItem = Required<MenuProps>['items'][number]

export const SellerSider: React.FC = () => {
  const { isCollapsed, toggleCollapse, orders, inventory, onSaleCount, openAddModal } =
    useSellerStore()
  const [searchText, setSearchText] = useState('')
  const [openKeys, setOpenKeys] = useState<string[]>(['orders_group'])
  const [selectedKeys, setSelectedKeys] = useState<string[]>(['dispatch_manage'])

  // 待发货数量
  const pendingDeliverCount = orders.filter((o) => o.status === 'pending').length
  // 库存预警数量
  const warningStockCount = inventory.filter((i) => i.status === 'warning').length

  // 菜单数据源定义
  const rawMenuItems: MenuItem[] = useMemo(
    () => [
      {
        key: 'overview',
        icon: <DashboardOutlined />,
        label: '工作台总览',
      },
      {
        key: 'todo_alerts',
        icon: <AlertOutlined />,
        label: (
          <div className="flex items-center justify-between">
            <span>待办中心与预警</span>
            <Badge count={4} size="small" />
          </div>
        ),
      },
      {
        key: 'goods_group',
        icon: <AppstoreOutlined />,
        label: (
          <div className="flex items-center justify-between">
            <span>商品管理</span>
            {warningStockCount > 0 && (
              <Badge count={warningStockCount} size="small" className="site-badge-warn" />
            )}
          </div>
        ),
        children: [
          {
            key: 'add_goods',
            label: '发布新商品',
          },
          {
            key: 'onsale_goods',
            label: `在售商品列表 (${onSaleCount})`,
          },
          {
            key: 'stock_monitor',
            label: `库存监控与预警 (${warningStockCount})`,
          },
          {
            key: 'audit_goods',
            label: '审核中与草稿箱 (1)',
          },
          {
            key: 'goods_qualification',
            label: '商品资质与品控认证',
          },
          {
            key: 'freight_template',
            label: '运费模板与地址库',
          },
        ],
      },
      {
        key: 'orders_group',
        icon: <CarOutlined />,
        label: (
          <div className="flex items-center justify-between">
            <span>订单履约</span>
            {pendingDeliverCount > 0 && <Badge count={pendingDeliverCount} size="small" />}
          </div>
        ),
        children: [
          {
            key: 'dispatch_manage',
            label: `待发货订单处理 (${pendingDeliverCount})`,
          },
          {
            key: 'all_orders',
            label: '全部订单综合查询',
          },
          {
            key: 'batch_print',
            label: '批量面单与打单',
          },
          {
            key: 'logistics_monitor',
            label: '物流异常与拦截预警 (1)',
          },
          {
            key: 'history_orders',
            label: '已发货与归档记录',
          },
          {
            key: 'multi_warehouse',
            label: '多仓库存调拨与自提',
          },
        ],
      },
      {
        key: 'aftersales_group',
        icon: <SyncOutlined />,
        label: (
          <div className="flex items-center justify-between">
            <span>售后与客诉</span>
            <Badge count={1} size="small" />
          </div>
        ),
        children: [
          { key: 'refund_audit', label: '退款/退货退款审核 (1)' },
          { key: 'exchange_manage', label: '换货与补发管理' },
          { key: 'platform_dispute', label: '平台纠纷与举证申诉' },
          { key: 'reviews_manage', label: '评价管理与官方回评' },
        ],
      },
      {
        key: 'marketing_group',
        icon: <ThunderboltOutlined />,
        label: '营销与促销',
        children: [
          { key: 'campaign_enroll', label: '官方大促/百亿补贴报名' },
          { key: 'coupon_config', label: '店铺优惠券配置' },
          { key: 'discount_packages', label: '满减满折/搭配套餐' },
          { key: 'member_private', label: '会员体系与私域运营' },
          { key: 'kol_distribution', label: '京粉/达人分销佣金' },
        ],
      },
      {
        key: 'service_group',
        icon: <CustomerServiceOutlined />,
        label: '客服管理',
        children: [
          { key: 'im_workbench', label: '在线客服接待台' },
          { key: 'quick_phrases', label: '快捷短语与智能应答' },
          { key: 'service_kpi', label: '接待质检与绩效统计' },
        ],
      },
      {
        key: 'shop_group',
        icon: <SkinOutlined />,
        label: '店铺运营',
        children: [
          { key: 'shop_decorate', label: 'PC/移动端微页面装修' },
          { key: 'asset_center', label: '素材中心 (图片与视频)' },
          { key: 'shop_categories', label: '店铺自定义商品分类' },
        ],
      },
      {
        key: 'analytics_group',
        icon: <LineChartOutlined />,
        label: '数据中心',
        children: [
          { key: 'realtime_screen', label: '实时经营监控大屏' },
          { key: 'goods_diagnosis', label: '商品流量与转化诊断' },
          { key: 'trade_funnel', label: '交易转化漏斗与客群' },
          { key: 'dsr_score', label: '店铺服务体验分 (DSR)' },
        ],
      },
      {
        key: 'finance_group',
        icon: <DollarOutlined />,
        label: '财务结算',
        children: [
          { key: 'settlement_bills', label: '营业收入结算对账' },
          { key: 'deposit_account', label: '店铺保证金账户' },
          { key: 'tax_invoices', label: '数电发票开具与管理' },
        ],
      },
      {
        key: 'settings_group',
        icon: <SettingOutlined />,
        label: '店铺设置',
        children: [
          { key: 'basic_info', label: '店铺基本信息与主体资质' },
          { key: 'sub_accounts', label: '子账号与角色权限分配' },
          { key: 'audit_logs', label: '关键操作日志与安全审计' },
        ],
      },
    ],
    [pendingDeliverCount, warningStockCount, onSaleCount],
  )

  // 手风琴折叠展开联动
  const onOpenChange = (keys: string[]) => {
    const latestOpenKey = keys.find((key) => !openKeys.includes(key))
    if (!latestOpenKey) {
      setOpenKeys([])
    } else {
      setOpenKeys([latestOpenKey])
    }
  }

  // 菜单点击响应
  const handleMenuClick: MenuProps['onClick'] = ({ key }) => {
    if (key === 'add_goods') {
      openAddModal()
      return
    }
    setSelectedKeys([key])
    message.success(`已切换至【${key}】控制台`)
  }

  return (
    <aside
      className={`sticky top-14 flex h-[calc(100vh-56px)] shrink-0 flex-col border-r border-[#f0f0f0] bg-white transition-all duration-200 ease-in-out ${
        isCollapsed ? 'w-[72px]' : 'w-[256px]'
      }`}
    >
      {/* 顶部搜索框 */}
      {!isCollapsed && (
        <div className="p-3 pb-2">
          <Input
            prefix={<SearchOutlined className="text-[#8c8c8c]" />}
            suffix={<kbd className="rounded border border-[#d9d9d9] bg-[#f5f5f5] px-1 py-0.5 text-[10px] text-[#8c8c8c]">Ctrl K</kbd>}
            placeholder="搜索功能 / 菜单"
            value={searchText}
            onChange={(e) => setSearchText(e.target.value)}
            className="text-xs"
            allowClear
          />
        </div>
      )}

      {/* 核心两级手风琴菜单 */}
      <div className="flex-1 overflow-y-auto overflow-x-hidden py-1">
        <Menu
          mode="inline"
          inlineCollapsed={isCollapsed}
          selectedKeys={selectedKeys}
          openKeys={isCollapsed ? [] : openKeys}
          onOpenChange={onOpenChange}
          onClick={handleMenuClick}
          items={rawMenuItems}
          className="border-none"
        />
      </div>

      {/* 底部收缩切换 Trigger */}
      <div
        onClick={toggleCollapse}
        className="flex h-11 cursor-pointer items-center justify-center gap-2 border-t border-[#f0f0f0] bg-white text-xs text-[#595959] transition-colors hover:bg-[#fafafa] hover:text-[#1677ff]"
        title={isCollapsed ? '展开侧边栏' : '收起侧边栏'}
      >
        {isCollapsed ? <RightOutlined /> : <LeftOutlined />}
        {!isCollapsed && <span>收起侧边栏</span>}
      </div>
    </aside>
  )
}
