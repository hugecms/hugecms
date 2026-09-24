import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Button, Tag, Space } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/taxonomies/tags')({
  component: TagsPage,
})

function TagsPage() {
  return (
    <PageContainer
      header={{
        title: '热门标签管理',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '栏目与分类' }, { title: '热门标签管理' }],
        },
      }}
    >
      <ProCard
        title="全站标签池"
        bordered
        extra={
          <Button type="primary" icon={<PlusOutlined />}>
            新建标签
          </Button>
        }
      >
        <p style={{ color: '#64748b' }}>
          用于前台多维交叉检索与 SEO 聚类打标，支持自动聚合高频搜索关键词。
        </p>
        <Space size="middle" wrap style={{ marginTop: 16 }}>
          <Tag color="magenta" style={{ padding: '6px 12px', fontSize: 13 }}>
            🏷️ Golang (32)
          </Tag>
          <Tag color="volcano" style={{ padding: '6px 12px', fontSize: 13 }}>
            🏷️ 微服务 (18)
          </Tag>
          <Tag color="orange" style={{ padding: '6px 12px', fontSize: 13 }}>
            🏷️ 高并发 (25)
          </Tag>
          <Tag color="gold" style={{ padding: '6px 12px', fontSize: 13 }}>
            🏷️ Docker (16)
          </Tag>
        </Space>
      </ProCard>
    </PageContainer>
  )
}
