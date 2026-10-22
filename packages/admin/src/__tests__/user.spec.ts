import { beforeEach, describe, expect, it, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useUserStore } from '@/stores/user'

vi.mock('@/api/auth', () => ({
  authLoginApi: vi.fn(),
  authLogoutApi: vi.fn(),
  authMeApi: vi.fn(),
}))

import { authLoginApi, authLogoutApi, authMeApi } from '@/api/auth'
import type { IAuthMeResponse } from '@/types/auth'

const mockMe: IAuthMeResponse = { id: 1, username: 'admin', email: null, isSuper: 1 }

describe('user store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.mocked(authLoginApi).mockReset()
    vi.mocked(authLogoutApi).mockReset()
    vi.mocked(authMeApi).mockReset()
  })

  it('初始状态：无 token、无用户信息、未登录', () => {
    const userStore = useUserStore()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
    expect(userStore.nickname).toBe('')
    expect(userStore.isSuper).toBe(false)
  })

  it('有 token 时 isLoggedIn 为 true，nickname 取用户名', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin', isSuper: 1 }

    expect(userStore.isLoggedIn).toBe(true)
    expect(userStore.nickname).toBe('admin')
    expect(userStore.isSuper).toBe(true)
  })

  it('userInfo 为空时 nickname 回退为空字符串', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'

    expect(userStore.nickname).toBe('')
  })

  it('reset 清空登录态', () => {
    const userStore = useUserStore()

    userStore.token = 'jwt-token'
    userStore.userInfo = { id: 1, username: 'admin' }
    userStore.reset()

    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
    expect(userStore.isLoggedIn).toBe(false)
  })

  it('login 成功：写入 token 与 me 用户信息', async () => {
    vi.mocked(authLoginApi).mockResolvedValue({ token: 'jwt-abc', expires: 1793259307 })
    vi.mocked(authMeApi).mockResolvedValue(mockMe)

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(authLoginApi).toHaveBeenCalledWith({ user_name: 'admin', password: '123456' })
    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toEqual({ id: 1, username: 'admin', email: null, isSuper: 1 })
    expect(userStore.isLoggedIn).toBe(true)
  })

  it('login 时拉取用户信息失败不阻塞登录', async () => {
    vi.mocked(authLoginApi).mockResolvedValue({ token: 'jwt-abc', expires: 1793259307 })
    vi.mocked(authMeApi).mockRejectedValue(new Error('me failed'))

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toBeNull()
  })

  it('login 接口失败：保持未登录状态', async () => {
    vi.mocked(authLoginApi).mockRejectedValue(new Error('用户名或密码错误'))

    const userStore = useUserStore()

    await expect(userStore.login({ username: 'admin', password: 'bad' })).rejects.toThrow(
      '用户名或密码错误',
    )
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })

  it('logout：调用接口后清空登录态', async () => {
    vi.mocked(authLogoutApi).mockResolvedValue(null)

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'
    userStore.userInfo = { id: 1, username: 'admin' }

    await userStore.logout()

    expect(authLogoutApi).toHaveBeenCalled()
    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
  })

  it('logout：接口失败也强制清空本地登录态', async () => {
    vi.mocked(authLogoutApi).mockRejectedValue(new Error('network down'))

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'

    await expect(userStore.logout()).rejects.toThrow('network down')
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })
})
