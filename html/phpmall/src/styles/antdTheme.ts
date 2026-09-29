import type { ThemeConfig } from 'antd'

/**
 * 全局 Ant Design 主题 Token 配置
 * 统一采用符合现代电商规范的蓝色基调与 8px 圆角体系
 */
export const antdTheme: ThemeConfig = {
  token: {
    colorPrimary: '#1677ff',
    colorSuccess: '#52c41a',
    colorWarning: '#faad14',
    colorError: '#ff4d4f',
    colorInfo: '#1677ff',
    colorTextBase: 'rgba(0, 0, 0, 0.88)',
    borderRadius: 8,
    borderRadiusSM: 6,
    fontFamily:
      "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif",
    fontSize: 14,
    wireframe: false,
  },
  components: {
    Menu: {
      itemHeight: 40,
      itemBorderRadius: 8,
      subMenuItemBorderRadius: 8,
      itemSelectedBg: '#e6f4ff',
      itemSelectedColor: '#1677ff',
      itemHoverBg: 'rgba(0, 0, 0, 0.05)',
      activeBarWidth: 0,
    },
    Button: {
      borderRadius: 6,
      controlHeight: 32,
    },
    Table: {
      headerBg: '#fafafa',
      headerColor: 'rgba(0, 0, 0, 0.88)',
      rowHoverBg: '#fafafa',
      borderColor: '#f0f0f0',
    },
    Card: {
      borderRadiusLG: 8,
    },
    Modal: {
      borderRadiusLG: 8,
    },
  },
}
