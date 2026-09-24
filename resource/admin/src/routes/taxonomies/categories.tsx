import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Tag, Button, Space } from 'antd'
import { PlusOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/taxonomies/categories')({
  component: CategoriesPage,
})

function CategoriesPage() {
  return (
    <PageContainer
      header={{
        title: '栏目分类树',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '栏目与分类' }, { title: '栏目分类树' }],
        },
      }}
    >
      <ProCard
        title="分类目录管理"
        bordered
        extra={
          <Button type="primary" icon={<PlusOutlined />}>
            新建分类
          </Button>
        }
      >
        <p style={{ color: '#64748b' }}>
          支持多层级父子结构、权重排序、Slug 别名与文章归属关系管理。
        </p>
        <Space size="middle" wrap style={{ marginTop: 16 }}>
          <Tag color="blue" style={{ padding: '6px 12px', fontSize: 13 }}>
            📁 后端研发 (14篇)
          </Tag>
          <Tag color="cyan" style={{ padding: '6px 12px', fontSize: 13 }}>
            📁 前端工程 (8篇)
          </Tag>
          <Tag color="purple" style={{ padding: '6px 12px', fontSize: 13 }}>
            📁 GoFrame 生态 (12篇)
          </Tag>
          <Tag color="green" style={{ padding: '6px 12px', fontSize: 13 }}>
            📁 未分类 (0篇)
          </Tag>
        </Space>
      </ProCard>
    </PageContainer>
  )
}
