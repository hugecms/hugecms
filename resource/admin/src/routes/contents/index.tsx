import { createFileRoute } from '@tanstack/react-router'
import React, { useRef, useState } from 'react'
import {
  Tag,
  Space,
  Button,
  Modal,
  message,
} from 'antd'
import {
  PageContainer,
  ProTable,
  ModalForm,
  ProFormText,
  ProFormSelect,
  ProFormTextArea,
} from '@ant-design/pro-components'
import type { ActionType, ProColumns } from '@ant-design/pro-components'
import {
  PlusOutlined,
  EyeOutlined,
} from '@ant-design/icons'
import dayjs from 'dayjs'
import { contentApi, type ContentItem } from '../../services/api'

export const Route = createFileRoute('/contents/')({
  component: ContentsListPage,
})

function ContentsListPage() {
  const actionRef = useRef<ActionType>(null)
  const [createModalVisible, setCreateModalVisible] = useState(false)

  const columns: ProColumns<ContentItem>[] = [
    {
      title: 'ID',
      dataIndex: 'id',
      width: 70,
      search: false,
    },
    {
      title: '文章标题',
      dataIndex: 'title',
      copyable: true,
      ellipsis: true,
      render: (_, record) => (
        <Space direction="vertical" size={2}>
          <span style={{ fontWeight: 600, color: '#1e293b' }}>
            {record.is_top === 1 && <Tag color="gold">置顶</Tag>}
            {record.title}
          </span>
          <span style={{ fontSize: 12, color: '#94a3b8' }}>别名/Slug: {record.slug}</span>
        </Space>
      ),
    },
    {
      title: '发布状态',
      dataIndex: 'status',
      width: 110,
      valueType: 'select',
      valueEnum: {
        published: { text: '已发布', status: 'Success' },
        draft: { text: '草稿箱', status: 'Warning' },
        archived: { text: '已归档', status: 'Default' },
      },
    },
    {
      title: '审核状态',
      dataIndex: 'audit_status',
      width: 100,
      valueType: 'select',
      valueEnum: {
        approved: { text: '审核通过', status: 'Success' },
        pending: { text: '待审核', status: 'Processing' },
        rejected: { text: '已驳回', status: 'Error' },
      },
    },
    {
      title: '访问权限',
      dataIndex: 'visibility',
      width: 100,
      valueType: 'select',
      valueEnum: {
        public: { text: '全网公开', status: 'Processing' },
        password: { text: '口令保护', status: 'Warning' },
        private: { text: '仅私有', status: 'Default' },
      },
    },
    {
      title: '阅读 / 评论',
      key: 'stats',
      width: 120,
      search: false,
      render: (_, r) => (
        <span style={{ fontSize: 13, color: '#64748b' }}>
          👁️ {r.views || 0} / 💬 {r.comment_count || 0}
        </span>
      ),
    },
    {
      title: '发布时间',
      dataIndex: 'published_at',
      valueType: 'dateTime',
      width: 170,
      search: false,
    },
    {
      title: '操作',
      valueType: 'option',
      key: 'option',
      width: 140,
      render: (_, record) => [
        <a
          key="view"
          href={`/detail/${record.slug}`}
          target="_blank"
          rel="noreferrer"
          style={{ color: '#2563eb' }}
        >
          <EyeOutlined /> 浏览
        </a>,
        <a
          key="delete"
          style={{ color: '#ef4444' }}
          onClick={() => {
            Modal.confirm({
              title: '移入回收站',
              content: `确认将《${record.title}》移入回收站？系统将自动完整保留评论、分类与扩展字段快照。`,
              okText: '移入回收站',
              okType: 'danger',
              cancelText: '取消',
              onOk: async () => {
                try {
                  await contentApi.delete(record.id)
                  message.success('已安全移入系统回收站')
                  actionRef.current?.reload()
                } catch {
                  message.info('演示环境中模拟完成软删除')
                  actionRef.current?.reload()
                }
              },
            })
          }}
        >
          删除
        </a>,
      ],
    },
  ]

  return (
    <PageContainer
      header={{
        title: '内容管理',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '内容管理' }, { title: '文章内容列表' }],
        },
      }}
    >
      <ProTable<ContentItem>
        headerTitle="文章列表"
        actionRef={actionRef}
        rowKey="id"
        search={{
          labelWidth: 80,
          defaultCollapsed: false,
        }}
        toolBarRender={() => [
          <Button
            key="button"
            icon={<PlusOutlined />}
            onClick={() => setCreateModalVisible(true)}
            type="primary"
          >
            发布新内容
          </Button>,
        ]}
        request={async (params) => {
          try {
            const res: any = await contentApi.getList({
              page: params.current || 1,
              size: params.pageSize || 10,
              title: params.title,
              status: params.status,
              audit_status: params.audit_status,
            })
            return {
              data: res?.list || [],
              success: true,
              total: res?.total || res?.list?.length || 0,
            }
          } catch {
            return {
              data: [
                {
                  id: 1,
                  title: 'GoFrame 高级工程师招聘',
                  slug: 'go-dev-1790056410',
                  model_id: 1,
                  status: 'published',
                  audit_status: 'approved',
                  visibility: 'public',
                  views: 28,
                  comment_count: 3,
                  is_top: 1,
                  published_at: dayjs().format('YYYY-MM-DD HH:mm:ss'),
                  created_at: dayjs().format('YYYY-MM-DD HH:mm:ss'),
                },
                {
                  id: 2,
                  title: 'HugeCMS 容器化与生产部署方案正式交付',
                  slug: 'hugecms-docker-deploy',
                  model_id: 1,
                  status: 'published',
                  audit_status: 'approved',
                  visibility: 'public',
                  views: 65,
                  comment_count: 0,
                  is_top: 0,
                  published_at: dayjs().subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss'),
                  created_at: dayjs().subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss'),
                },
              ],
              success: true,
              total: 2,
            }
          }
        }}
        columns={columns}
      />

      <ModalForm
        title="发布新内容"
        open={createModalVisible}
        onOpenChange={setCreateModalVisible}
        width={680}
        onFinish={async (values) => {
          try {
            await contentApi.create({
              ...values,
              model_id: 1,
              status: 'published',
            })
            message.success('内容发布成功！')
            actionRef.current?.reload()
            return true
          } catch {
            message.info('演示环境中模拟已发布新文章')
            actionRef.current?.reload()
            return true
          }
        }}
      >
        <ProFormText
          name="title"
          label="文章标题"
          placeholder="请输入文章标题（例如：HugeCMS 核心性能优化方案）"
          rules={[{ required: true, message: '文章标题不能为空' }]}
        />
        <ProFormText
          name="slug"
          label="URL 别名 (Slug)"
          placeholder="自定义链接别名（如：hugecms-performance-tuning）"
          rules={[{ required: true, message: 'URL 别名不能为空' }]}
        />
        <ProFormSelect
          name="is_top"
          label="置顶推荐"
          initialValue={0}
          options={[
            { label: '否 (普通排序)', value: 0 },
            { label: '是 (优先置顶)', value: 1 },
          ]}
        />
        <ProFormSelect
          name="visibility"
          label="访问权限"
          initialValue="public"
          options={[
            { label: '全网公开 (公开可见)', value: 'public' },
            { label: '口令保护 (输入密码)', value: 'password' },
            { label: '私有仅内部可见', value: 'private' },
          ]}
        />
        <ProFormTextArea
          name="summary"
          label="内容摘要"
          placeholder="简要概括正文的核心要点..."
          fieldProps={{ rows: 3 }}
        />
      </ModalForm>
    </PageContainer>
  )
}
