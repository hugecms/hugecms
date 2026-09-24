import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Button, Table, Tag } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/operations/links')({
  component: ShortLinksPage,
})

function ShortLinksPage() {
  const mockLinks = [
    {
      id: 1,
      code: 'spring2026',
      target: 'http://localhost:8000/detail/go-dev-1790056410',
      views: 142,
      created_at: '2026-09-20',
    },
    {
      id: 2,
      code: 'hired',
      target: 'http://localhost:8000/category/backend_1790056410',
      views: 89,
      created_at: '2026-09-21',
    },
  ]

  const columns = [
    { title: 'ID', dataIndex: 'id', width: 60 },
    {
      title: '短链标识 (Code)',
      dataIndex: 'code',
      render: (c: string) => (
        <a href={`/s/${c}`} target="_blank" rel="noreferrer" style={{ color: '#2563eb' }}>
          /s/{c}
        </a>
      ),
    },
    { title: '目标重定向 URL', dataIndex: 'target', ellipsis: true },
    { title: '累计点击 PV', dataIndex: 'views', width: 120, render: (v: number) => <Tag color="blue">{v} 次</Tag> },
    { title: '创建时间', dataIndex: 'created_at', width: 140 },
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
        title: '推广短链追踪',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '运营与互动' }, { title: '推广短链追踪' }],
        },
      }}
    >
      <ProCard
        title="营销追踪短链"
        bordered
        extra={
          <Button type="primary" icon={<PlusOutlined />}>
            生成短链
          </Button>
        }
      >
        <Table rowKey="id" dataSource={mockLinks} columns={columns} pagination={false} />
      </ProCard>
    </PageContainer>
  )
}
