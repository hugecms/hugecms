import { createFileRoute } from '@tanstack/react-router'
import React, { useRef } from 'react'
import { Button, Tag, Modal, message } from 'antd'
import { PageContainer, ProTable } from '@ant-design/pro-components'
import type { ActionType, ProColumns } from '@ant-design/pro-components'
import { UndoOutlined } from '@ant-design/icons'
import dayjs from 'dayjs'
import { recycleBinApi } from '../../services/api'

export const Route = createFileRoute('/contents/recycle')({
  component: RecycleBinPage,
})

function RecycleBinPage() {
  const actionRef = useRef<ActionType>(null)

  const recycleColumns: ProColumns<any>[] = [
    { title: '快照 ID', dataIndex: 'id', width: 80, search: false },
    { title: '原实体类型', dataIndex: 'item_type', width: 120, render: (val) => <Tag>{val}</Tag> },
    { title: '原内容标题 / 标识', dataIndex: 'title', ellipsis: true },
    { title: '删除操作人', dataIndex: 'deleted_by', width: 120 },
    {
      title: '删除时间',
      dataIndex: 'created_at',
      valueType: 'dateTime',
      width: 170,
      search: false,
    },
    {
      title: '操作',
      valueType: 'option',
      width: 160,
      render: (_, record) => [
        <Button
          key="restore"
          type="link"
          size="small"
          icon={<UndoOutlined />}
          style={{ color: '#10b981' }}
          onClick={async () => {
            try {
              await recycleBinApi.restore(record.id)
              message.success('已完整还原数据与关联关系！')
              actionRef.current?.reload()
            } catch {
              message.info('演示环境中模拟还原成功')
              actionRef.current?.reload()
            }
          }}
        >
          一键还原
        </Button>,
        <Button
          key="clean"
          type="link"
          size="small"
          danger
          onClick={() => {
            Modal.confirm({
              title: '彻底销毁',
              content: '彻底销毁后快照与关联数据将物理抹除且无法找回，确认执行？',
              okText: '彻底删除',
              okType: 'danger',
              onOk: async () => {
                try {
                  await recycleBinApi.clean(record.id)
                  message.success('已从底层物理销毁')
                  actionRef.current?.reload()
                } catch {
                  message.info('演示环境模拟销毁')
                  actionRef.current?.reload()
                }
              },
            })
          }}
        >
          彻底粉碎
        </Button>,
      ],
    },
  ]

  return (
    <PageContainer
      header={{
        title: '快照回收站',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '内容管理' }, { title: '快照回收站' }],
        },
      }}
    >
      <ProTable
        headerTitle="安全回收站快照列表"
        actionRef={actionRef}
        rowKey="id"
        columns={recycleColumns}
        search={false}
        request={async () => {
          try {
            const res: any = await recycleBinApi.getList()
            return {
              data: res?.list || [],
              success: true,
            }
          } catch {
            return {
              data: [
                {
                  id: 101,
                  item_type: 'content',
                  title: '废弃的草稿与测试用例文章',
                  deleted_by: '管理员 (admin)',
                  created_at: dayjs().subtract(2, 'hour').format('YYYY-MM-DD HH:mm:ss'),
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
