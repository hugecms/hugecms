import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard, ProTable } from '@ant-design/pro-components'
import { Tag, Button } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/system/users')({
  component: UsersPage,
})

function UsersPage() {
  const mockUsers = [
    {
      id: 1,
      username: 'admin',
      nickname: '超级管理员',
      email: 'admin@hugecms.com',
      role: 'super_admin',
      status: 1,
      created_at: '2026-09-20 10:00',
    },
    {
      id: 2,
      username: 'editor_zhang',
      nickname: '张主编',
      email: 'zhang@hugecms.com',
      role: 'editor',
      status: 1,
      created_at: '2026-09-21 14:30',
    },
  ]

  const columns = [
    { title: 'UID', dataIndex: 'id', width: 70 },
    { title: '用户名', dataIndex: 'username', width: 140 },
    { title: '姓名 / 昵称', dataIndex: 'nickname', width: 140 },
    { title: '联系邮箱', dataIndex: 'email' },
    {
      title: '所属角色',
      dataIndex: 'role',
      width: 140,
      render: (r: string) => (r === 'super_admin' ? <Tag color="gold">超级管理员</Tag> : <Tag color="blue">内容编辑</Tag>),
    },
    {
      title: '账号状态',
      dataIndex: 'status',
      width: 100,
      render: () => <Tag color="success">正常启用</Tag>,
    },
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
        title: '用户管理',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '系统与权限' }, { title: '用户管理' }],
        },
      }}
    >
      <ProTable
        headerTitle="系统操作员账号"
        rowKey="id"
        dataSource={mockUsers}
        columns={columns}
        search={false}
        toolBarRender={() => [
          <Button key="add" type="primary" icon={<PlusOutlined />}>
            新建用户
          </Button>,
        ]}
      />
    </PageContainer>
  )
}
