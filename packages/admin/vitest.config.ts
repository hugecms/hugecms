import { fileURLToPath } from 'node:url'
import { mergeConfig, defineConfig, configDefaults } from 'vitest/config'
import viteConfig from './vite.config.ts'

export default mergeConfig(
  viteConfig,
  defineConfig({
    test: {
      environment: 'jsdom',
      exclude: [...configDefaults.exclude, 'e2e/**'],
      root: fileURLToPath(new URL('./', import.meta.url)),
      // element-plus 按需 resolver 会注入样式导入，externalize 时 node 无法加载 .css，
      // inline 进 vite 转换管线处理
      server: {
        deps: {
          inline: [/element-plus/],
        },
      },
    },
  }),
)
