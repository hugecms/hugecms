import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { Button, Tag } from 'antd'
import { PageContainer, ProTable } from '@ant-design/pro-components'
import type { ProColumns } from '@ant-design/pro-components'
import dayjs from 'dayjs'
import { modelApi } from '../../services/api'

export const Route = createFileRoute('/contents/models')({
  component: ModelsListPage,
})

function ModelsListPage() {
  const modelColumns: ProColumns<any>[] = [
    { title: '模型 ID', dataIndex: 'id', width: 80, search: false },
    { title: '模型名称', dataIndex: 'name', copyable: true },
    { title: '模型标识 (Alias)', dataIndex: 'alias', render: (val) => <Tag color="blue">{val}</Tag> },
    { title: '物理数据表', dataIndex: 'table_name', render: (val) => <code>{val}</code> },
    { title: '扩展字段数', dataIndex: 'fields_count', search: false, width: 110 },
    {
      title: '创建时间',
      dataIndex: 'created_at',
      valueType: 'dateTime',
      search: false,
      width: 170,
    },
    {
      title: '操作',
      valueType: 'option',
      width: 140,
      render: () => [
        <Button key="fields" type="link" size="small" style={{ color: '#2563eb' }}>
          字段 DDL 设计
        </Button>,
      ],
    },
  ]

  return (
    <PageContainer
      header={{
        title: '内容模型设计',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '内容管理' }, { title: '内容模型设计' }],
        },
      }}
    >
      <ProTable
        headerTitle="模型清单"
        rowKey="id"
        columns={modelColumns}
        search={false}
        request={async () => {
          try {
            const res: any = await modelApi.getList()
            return {
              data: res || [],
              success: true,
            }
          } catch {
            return {
              data: [
                {
                  id: 1,
                  name: '文章资讯模型',
                  alias: 'article',
                  table_name: 'data_article',
                  fields_count: 5,
                  created_at: dayjs().subtract(7, 'day').format('YYYY-MM-DD HH:mm:ss'),
                },
                {
                  id: 2,
                  name: '招聘岗位模型',
                  alias: 'job',
                  table_name: 'data_job',
                  fields_count: 4,
                  created_at: dayjs().subtract(5, 'day').format('YYYY-MM-DD HH:mm:ss'),
                },
              ],
              success: true,
            }
          }
        }}
      />
    </PageContainer>
  )
}
