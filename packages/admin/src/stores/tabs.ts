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

  /** 关闭其他（保留首页与指定标签） */
  function closeOthers(keepPath: string): void {
    tabs.value = tabs.value.filter((tab) => tab.path === HOME.path || tab.path === keepPath)
  }

  /** 关闭右侧（保留首页、指定标签及其左侧） */
  function closeRight(path: string): void {
    const index = tabs.value.findIndex((tab) => tab.path === path)
    if (index === -1) {
      return
    }
    tabs.value = tabs.value.filter((tab, idx) => idx <= index || tab.path === HOME.path)
  }

  /** 全部关闭（回到首页） */
  function closeAll(): void {
    reset()
  }

  function reset(): void {
    tabs.value = [{ ...HOME }]
  }

  return { tabs, addTab, closeTab, closeOthers, closeRight, closeAll, reset }
})
