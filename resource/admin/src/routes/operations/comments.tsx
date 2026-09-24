import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Button, Table, Tag, Space } from 'antd'
import { CheckOutlined, CloseOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/operations/comments')({
  component: CommentsPage,
})

function CommentsPage() {
  const mockComments = [
    {
      id: 1,
      author: '热心访客',
      email: 'visitor@example.com',
      content: '文章讲解得很透彻，期待后续关于微服务治理的分享！',
      target: 'GoFrame 高级工程师招聘',
      status: 'pending',
      created_at: '2026-09-22 17:20',
    },
    {
      id: 2,
      author: '运维小哥',
      email: 'devops@example.com',
      content: '请教一下 Docker Compose 部署时数据卷备份的最佳策略？',
      target: 'HugeCMS 容器化与生产部署方案',
      status: 'approved',
      created_at: '2026-09-22 16:05',
    },
  ]

  const columns = [
    { title: 'ID', dataIndex: 'id', width: 60 },
    { title: '评论人', dataIndex: 'author', width: 120 },
    { title: '评论内容', dataIndex: 'content' },
    { title: '关联文章', dataIndex: 'target', width: 220 },
    {
      title: '审核状态',
      dataIndex: 'status',
      width: 100,
      render: (st: string) =>
        st === 'approved' ? (
          <Tag color="success">已审核</Tag>
        ) : (
          <Tag color="warning">待审核</Tag>
        ),
    },
    { title: '提交时间', dataIndex: 'created_at', width: 160 },
    {
      title: '操作',
      key: 'action',
      width: 160,
      render: () => (
        <Space>
          <Button type="link" size="small" icon={<CheckOutlined />} style={{ color: '#10b981' }}>
            通过
          </Button>
          <Button type="link" size="small" danger icon={<CloseOutlined />}>
            驳回
          </Button>
        </Space>
      ),
    },
  ]

  return (
    <PageContainer
      header={{
        title: '访客评论审核',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '运营与互动' }, { title: '访客评论审核' }],
        },
      }}
    >
      <ProCard title="评论互动审核中心" bordered>
        <Table rowKey="id" dataSource={mockComments} columns={columns} pagination={false} />
      </ProCard>
    </PageContainer>
  )
}
