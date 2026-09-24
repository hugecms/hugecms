import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard, ProForm, ProFormText, ProFormTextArea } from '@ant-design/pro-components'
import { Button, message, Space } from 'antd'

export const Route = createFileRoute('/appearance/seo')({
  component: SeoPage,
})

function SeoPage() {
  return (
    <PageContainer
      header={{
        title: '全站 SEO 配置',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '站点与界面' }, { title: '全站 SEO 配置' }],
        },
      }}
    >
      <ProCard title="搜索引擎优化设定" bordered style={{ maxWidth: 800 }}>
        <ProForm
          initialValues={{
            title_pattern: '{title} - {site_name}',
            keywords: 'HugeCMS,Golang,GoFrame,内容管理系统,高性能CMS',
            description: '基于 GoFrame 2.x 与 React 构建的企业级高性能动态内容管理系统。',
          }}
          onFinish={async () => {
            message.success('SEO 设置已成功保存！前台 sitemap 与 meta 即时生效')
          }}
        >
          <ProFormText
            name="title_pattern"
            label="标题规则模板"
            tooltip="变量示例: {title} 表示页面标题, {site_name} 表示站点名称"
          />
          <ProFormText name="keywords" label="全站默认关键词" />
          <ProFormTextArea name="description" label="全站默认描述" fieldProps={{ rows: 3 }} />
        </ProForm>

        <div style={{ marginTop: 24, paddingTop: 16, borderTop: '1px solid #f1f5f9' }}>
          <Space>
            <Button href="/sitemap.xml" target="_blank">
              查看实时 Sitemap 站点地图 ↗
            </Button>
            <Button href="/robots.txt" target="_blank">
              查看 Robots 规则 ↗
            </Button>
          </Space>
        </div>
      </ProCard>
    </PageContainer>
  )
}
