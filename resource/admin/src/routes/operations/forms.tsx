import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Button, Table, Tag } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/operations/forms')({
  component: FormsPage,
})

function FormsPage() {
  const mockForms = [
    {
      id: 1,
      title: '商务合作咨询',
      code: 'business_contact',
      fields_count: 5,
      records_count: 42,
      status: 1,
    },
    {
      id: 2,
      title: '招聘职位意向提报',
      code: 'job_apply',
      fields_count: 4,
      records_count: 18,
      status: 1,
    },
  ]

  const columns = [
    { title: 'ID', dataIndex: 'id', width: 60 },
    { title: '表单名称', dataIndex: 'title' },
    { title: '调用代码 (Code)', dataIndex: 'code', render: (c: string) => <code>{c}</code> },
    { title: '字段数', dataIndex: 'fields_count', width: 100 },
    { title: '收集数据量', dataIndex: 'records_count', width: 120 },
    {
      title: '运行状态',
      dataIndex: 'status',
      width: 100,
      render: () => <Tag color="green">开启收集中</Tag>,
    },
    {
      title: '操作',
      key: 'action',
      width: 150,
      render: () => (
        <Button type="link" size="small">
          查看反馈记录
        </Button>
      ),
    },
  ]

  return (
    <PageContainer
      header={{
        title: '自定义反馈表单',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '运营与互动' }, { title: '自定义反馈表单' }],
        },
      }}
    >
      <ProCard
        title="全站表单设计器"
        bordered
        extra={
          <Button type="primary" icon={<PlusOutlined />}>
            新建表单
          </Button>
        }
      >
        <Table rowKey="id" dataSource={mockForms} columns={columns} pagination={false} />
      </ProCard>
    </PageContainer>
  )
}
