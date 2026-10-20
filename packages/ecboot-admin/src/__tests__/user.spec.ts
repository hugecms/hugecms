import { beforeEach, describe, expect, it } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useUserStore } from '@/stores/user'

describe('user store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('初始状态：无 token、无用户信息、未登录', () => {
    const userStore = useUserStore()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
    expect(userStore.nickname).toBe('')
  })

  it('有 token 时 isLoggedIn 为 true，nickname 优先取昵称', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin', nickname: '管理员' }

    expect(userStore.isLoggedIn).toBe(true)
    expect(userStore.nickname).toBe('管理员')
  })

  it('userInfo 为空时 nickname 回退为空字符串', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'

    expect(userStore.nickname).toBe('')
  })

  it('reset 清空登录态', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin', nickname: '管理员' }
    userStore.reset()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
  })
})
