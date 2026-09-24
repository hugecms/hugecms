import { createFileRoute } from '@tanstack/react-router'
import React from 'react'
import { PageContainer, ProCard } from '@ant-design/pro-components'
import { Upload, Button, message } from 'antd'
import { InboxOutlined } from '@ant-design/icons'

export const Route = createFileRoute('/assets/attachments')({
  component: AttachmentsPage,
})

function AttachmentsPage() {
  const { Dragger } = Upload

  return (
    <PageContainer
      header={{
        title: '媒体附件管理',
        breadcrumb: {
          items: [{ title: '控制台' }, { title: '资源素材库' }, { title: '媒体附件管理' }],
        },
      }}
    >
      <ProCard title="文件上传与素材库" bordered>
        <Dragger
          name="file"
          action="/api/common/attachment/upload"
          onChange={(info) => {
            const { status } = info.file
            if (status === 'done') {
              message.success(`${info.file.name} 附件上传成功！`)
            } else if (status === 'error') {
              message.error(`${info.file.name} 上传失败`)
            }
          }}
          style={{ padding: 24 }}
        >
          <p className="ant-upload-drag-icon" style={{ fontSize: 42, color: '#2563eb' }}>
            <InboxOutlined />
          </p>
          <p className="ant-upload-text" style={{ fontSize: 16, fontWeight: 600 }}>
            点击或将图片、附件拖拽到此区域上传
          </p>
          <p className="ant-upload-hint" style={{ color: '#64748b' }}>
            支持图片、音视频与常用文档。系统自动建立引用关系防误删。
          </p>
        </Dragger>
      </ProCard>
    </PageContainer>
  )
}
