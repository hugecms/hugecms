import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import {
  PageContainer,
  ProCard,
  StatisticCard,
} from '@ant-design/pro-components'
import {
  FileTextOutlined,
  EyeOutlined,
  CommentOutlined,
  SafetyCertificateOutlined,
} from '@ant-design/icons'
import { Button, Space, Typography } from 'antd'
import { Link } from '@tanstack/react-router'

const { Paragraph } = Typography

export const Route = createFileRoute('/')({
  component: DashboardPage,
})

function DashboardPage() {
  return (
    <PageContainer
      header={{
        title: '工作台概览',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '工作台概览' }],
        },
      }}
    >
      <ProCard.Group direction="row" gutter={16} style={{ marginBottom: 20 }}>
        <StatisticCard
          statistic={{
            title: '全站已发布内容',
            value: 128,
            icon: <FileTextOutlined style={{ color: '#2563eb', fontSize: 26 }} />,
          }}
        />
        <StatisticCard.Divider />
        <StatisticCard
          statistic={{
            title: '今日累计访问 (PV)',
            value: '4,620',
            status: 'processing',
            icon: <EyeOutlined style={{ color: '#0ea5e9', fontSize: 26 }} />,
          }}
        />
        <StatisticCard.Divider />
        <StatisticCard
          statistic={{
            title: '待审核访客评论',
            value: 3,
            status: 'warning',
            icon: <CommentOutlined style={{ color: '#f59e0b', fontSize: 26 }} />,
          }}
        />
        <StatisticCard.Divider />
        <StatisticCard
          statistic={{
            title: '系统运行状态',
            value: '正常运行中',
            status: 'success',
            icon: <SafetyCertificateOutlined style={{ color: '#10b981', fontSize: 26 }} />,
          }}
        />
      </ProCard.Group>

      <ProCard title="快捷业务导航" bordered headerBordered>
        <Paragraph style={{ color: '#64748b' }}>
          欢迎登录 <strong>HugeCMS</strong> 内容管理平台。您可以快速进入常用功能开展日常运营与内容编发：
        </Paragraph>
        <Space size="middle" style={{ marginTop: 12 }}>
          <Link to="/contents">
            <Button type="primary">管理内容列表</Button>
          </Link>
          <Link to="/contents/models">
            <Button>模型与字段设计</Button>
          </Link>
          <Link to="/taxonomies/categories">
            <Button>栏目分类管理</Button>
          </Link>
          <Link to="/operations/comments">
            <Button>审核访客评论</Button>
          </Link>
          <Button href="/" target="_blank">
            前台站点预览 ↗
          </Button>
        </Space>
      </ProCard>
    </PageContainer>
  )
}
