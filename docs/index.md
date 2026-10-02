---
# https://vitepress.dev/reference/default-theme-home-page
layout: home

hero:
  name: HugeCMS
  text: 基于 Laravel 的内容管理系统
  tagline: 现代技术栈 · 云原生部署 · 面向中小内容团队的一站式内容平台
  actions:
    - theme: brand
      text: 产品需求文档
      link: /prd
    - theme: alt
      text: 技术实现文档
      link: /technical-design

features:
  - icon: 📝
    title: 内容工作台
    details: Markdown / 富文本双模式编辑器、草稿自动保存、定时发布、版本修订与回收站，多人协作不丢稿。
  - icon: 🔐
    title: 精细权限
    details: 内置超级管理员 / 管理员 / 编辑 / 作者 / 审核员五角色 RBAC，全部操作可审计。
  - icon: ☁️
    title: 云原生部署
    details: App Engine 标准环境 + Cloud SQL + 对象存储，按量计费、免运维，小团队也用得起。
  - icon: 🧩
    title: 工程化底座
    details: 领域驱动分层 + DevTools 代码生成流水线，接口即文档（OpenAPI 注解自动生成路由与契约）。
---
