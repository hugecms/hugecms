import React from 'react'
import {
  ConfigProvider,
  Tag,
  Space,
  Button,
  Dropdown,
  message,
} from 'antd'
import {
  ProLayout,
} from '@ant-design/pro-components'
import {
  DashboardOutlined,
  FileTextOutlined,
  TagsOutlined,
  FormOutlined,
  PictureOutlined,
  ShareAltOutlined,
  SafetyCertificateOutlined,
  LogoutOutlined,
} from '@ant-design/icons'
import zhCN from 'antd/locale/zh_CN'
import { useNavigate, useLocation } from '@tanstack/react-router'

// 按照国内后台高频操作与业务直觉规范菜单：
// 1. 仪表盘 2. 内容管理 3. 栏目与分类 4. 运营与互动 5. 资源素材库 6. 站点与界面 7. 系统与权限
export const menuRoutes = [
  {
    path: '/',
    name: '工作台概览',
    icon: <DashboardOutlined />,
  },
  {
    path: '/contents',
    name: '内容管理',
    icon: <FileTextOutlined />,
    children: [
      { path: '/contents', name: '文章内容列表' },
      { path: '/contents/models', name: '内容模型设计' },
      { path: '/contents/recycle', name: '快照回收站' },
    ],
  },
  {
    path: '/taxonomies',
    name: '栏目与分类',
    icon: <TagsOutlined />,
    children: [
      { path: '/taxonomies/categories', name: '栏目分类树' },
      { path: '/taxonomies/tags', name: '热门标签管理' },
    ],
  },
  {
    path: '/operations',
    name: '运营与互动',
    icon: <FormOutlined />,
    children: [
      { path: '/operations/comments', name: '访客评论审核' },
      { path: '/operations/forms', name: '自定义反馈表单' },
      { path: '/operations/links', name: '推广短链追踪' },
    ],
  },
  {
    path: '/assets',
    name: '资源素材库',
    icon: <PictureOutlined />,
    children: [
      { path: '/assets/attachments', name: '媒体附件管理' },
    ],
  },
  {
    path: '/appearance',
    name: '站点与界面',
    icon: <ShareAltOutlined />,
    children: [
      { path: '/appearance/menus', name: '导航菜单设置' },
      { path: '/appearance/friend-links', name: '友情链接管理' },
      { path: '/appearance/seo', name: '全站 SEO 配置' },
    ],
  },
  {
    path: '/system',
    name: '系统与权限',
    icon: <SafetyCertificateOutlined />,
    children: [
      { path: '/system/users', name: '用户管理' },
      { path: '/system/roles', name: '角色权限策略' },
      { path: '/system/settings', name: '站点全局参数' },
      { path: '/system/audit-logs', name: '操作审计日志' },
    ],
  },
]

export function AdminLayout({ children }: { children: React.ReactNode }) {
  const navigate = useNavigate()
  const location = useLocation()

  const user = {
    name: '超级管理员',
    avatar: 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png',
    email: 'admin@hugecms.com',
  }

  return (
    <ConfigProvider
      locale={zhCN}
      theme={{
        token: {
          colorPrimary: '#2563eb',
          borderRadius: 8,
          fontFamily: `-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial`,
        },
      }}
    >
      <ProLayout
        title="HugeCMS"
        logo={
          <div
            style={{
              width: 28,
              height: 28,
              borderRadius: 6,
              background: 'linear-gradient(135deg, #2563eb, #3b82f6)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#fff',
              fontWeight: 800,
              fontSize: 15,
            }}
          >
            H
          </div>
        }
        route={{
          routes: menuRoutes,
        }}
        location={{
          pathname: location.pathname,
        }}
        menuItemRender={(item, dom) => (
          <a
            onClick={(e) => {
              e.preventDefault()
              if (item.path) {
                navigate({ to: item.path as any })
              }
            }}
          >
            {dom}
          </a>
        )}
        avatarProps={{
          src: user.avatar,
          size: 'small',
          title: user.name,
          render: (_, dom) => (
            <Dropdown
              menu={{
                items: [
                  {
                    key: 'profile',
                    label: `${user.name} (${user.email})`,
                    disabled: true,
                  },
                  { type: 'divider' },
                  {
                    key: 'logout',
                    icon: <LogoutOutlined />,
                    label: '退出登录',
                    danger: true,
                    onClick: () => {
                      localStorage.removeItem('hugecms_token')
                      message.success('已安全退出登录')
                    },
                  },
                ],
              }}
            >
              {dom}
            </Dropdown>
          ),
        }}
        actionsRender={() => [
          <Button
            key="portal"
            type="link"
            href="/"
            target="_blank"
            style={{ color: '#475569', fontSize: 13 }}
          >
            🌐 浏览前台主站
          </Button>,
        ]}
        layout="mix"
        fixSiderbar
        splitMenus={false}
        style={{ minHeight: '100vh' }}
      >
        {children}
      </ProLayout>
    </ConfigProvider>
  )
}
