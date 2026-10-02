import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  lang: 'zh-CN',
  title: 'HugeCMS',
  description: '基于 Laravel 13 的内容管理系统：产品文档、技术方案与工程规范',
  srcDir: '.',
  // 指向 srcDir 之外的仓库内链接（如 ../deployments/，规范化为 ./../xxx）不参与构建，跳过死链校验
  ignoreDeadLinks: [/^(\.\/)?\.\.\//],
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: '首页', link: '/' },
      { text: '产品需求', link: '/prd' },
      { text: '技术方案', link: '/technical-design' },
    ],

    sidebar: {
      '/': [
        {
          text: '产品',
          items: [
            { text: '产品需求文档（PRD）', link: '/prd' },
          ],
        },
        {
          text: '研发',
          items: [
            { text: '技术实现文档', link: '/technical-design' },
            { text: '工程编码军规（技术方案 §3）', link: '/technical-design#_3-工程落地与编码军规-engineering-conventions' },
          ],
        },
      ],
    },

    outline: { level: [2, 3], label: '本页目录' },
    docFooter: { prev: '上一篇', next: '下一篇' },
    lastUpdated: { text: '最后更新', formatOptions: { dateStyle: 'short', timeStyle: 'short' } },
    search: { provider: 'local', options: { translations: { button: { buttonText: '搜索文档', buttonAriaLabel: '搜索' }, modal: { noResultsText: '无结果' } } } },
  },
})
