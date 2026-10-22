import { ref } from 'vue'
import { defineStore } from 'pinia'

export interface TabItem {
  path: string
  title: string
  closable: boolean
}

const HOME: TabItem = { path: '/dashboard', title: '仪表盘', closable: false }

/** 页面多标签：内存态（刷新重置，避免持久化出脏标签） */
export const useTabsStore = defineStore('tabs', () => {
  const tabs = ref<TabItem[]>([{ ...HOME }])

  function addTab(path: string, title: string): void {
    if (!path || tabs.value.some((tab) => tab.path === path)) {
      return
    }
    tabs.value.push({ path, title, closable: path !== HOME.path })
  }

  /** 关闭标签，返回应激活的相邻标签路径 */
  function closeTab(path: string): string {
    const index = tabs.value.findIndex((tab) => tab.path === path)
    if (index === -1) {
      return HOME.path
    }
    tabs.value.splice(index, 1)
    const next = tabs.value[Math.min(index, tabs.value.length - 1)]
    return next?.path ?? HOME.path
  }

  function reset(): void {
    tabs.value = [{ ...HOME }]
  }

  return { tabs, addTab, closeTab, reset }
})
