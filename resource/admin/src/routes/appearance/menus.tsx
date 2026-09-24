import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Button, Table, Tag } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/appearance/menus')({
  component: MenusPage,
})

function MenusPage() {
  const mockMenus = [
    { id: 1, title: '首页', link_type: 'custom', link_url: '/', sort: 1 },
    { id: 2, title: '后端研发', link_type: 'term', link_url: '/category/backend_1790056410', sort: 2 },
    { id: 3, title: '关于我们', link_type: 'custom', link_url: '/about', sort: 3 },
  ]

  const columns = [
    { title: '排序', dataIndex: 'sort', width: 80 },
    { title: '菜单标题', dataIndex: 'title', width: 180 },
    {
      title: '链接类型',
      dataIndex: 'link_type',
      width: 120,
      render: (t: string) => (t === 'term' ? <Tag color="blue">栏目链接</Tag> : <Tag>自定义URL</Tag>),
    },
    { title: '跳转路径', dataIndex: 'link_url' },
    {
      title: '操作',
      key: 'action',
      width: 120,
      render: () => <Button type="link" size="small">编辑</Button>,
    },
  ]

  return (
    <PageContainer
      header={{
        title: '导航菜单设置',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '站点与界面' }, { title: '导航菜单设置' }],
        },
      }}
    >
      <ProCard
        title="主导航编排 (Header Nav)"
        bordered
        extra={
          <Button type="primary" icon={<PlusOutlined />}>
            添加菜单项
          </Button>
        }
      >
        <Table rowKey="id" dataSource={mockMenus} columns={columns} pagination={false} />
      </ProCard>
    </PageContainer>
  )
}
