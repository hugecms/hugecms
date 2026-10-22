import { beforeEach, describe, expect, it } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useTabsStore } from '@/stores/tabs'

describe('tabs store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('addTab 去重，首页不可关闭', () => {
    const store = useTabsStore()

    store.addTab('/product/brand', '商品品牌')
    store.addTab('/product/brand', '商品品牌')

    expect(store.tabs.map((tab) => tab.path)).toEqual(['/dashboard', '/product/brand'])
    expect(store.tabs[0]!.closable).toBe(false)
    expect(store.tabs[1]!.closable).toBe(true)
  })

  it('closeTab 返回相邻标签路径', () => {
    const store = useTabsStore()
    store.addTab('/a', 'A')
    store.addTab('/b', 'B')

    const next = store.closeTab('/b')

    expect(next).toBe('/a')
    expect(store.tabs.map((tab) => tab.path)).toEqual(['/dashboard', '/a'])
  })

  it('closeOthers 保留首页与指定标签', () => {
    const store = useTabsStore()
    store.addTab('/a', 'A')
    store.addTab('/b', 'B')
    store.addTab('/c', 'C')

    store.closeOthers('/b')

    expect(store.tabs.map((tab) => tab.path)).toEqual(['/dashboard', '/b'])
  })

  it('closeRight 保留左侧与首页', () => {
    const store = useTabsStore()
    store.addTab('/a', 'A')
    store.addTab('/b', 'B')
    store.addTab('/c', 'C')

    store.closeRight('/a')

    expect(store.tabs.map((tab) => tab.path)).toEqual(['/dashboard', '/a'])
  })

  it('closeAll 回到首页', () => {
    const store = useTabsStore()
    store.addTab('/a', 'A')

    store.closeAll()

    expect(store.tabs).toEqual([{ path: '/dashboard', title: '仪表盘', closable: false }])
  })
})
