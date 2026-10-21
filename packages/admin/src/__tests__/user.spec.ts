import { beforeEach, describe, expect, it, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useUserStore } from '@/stores/user'

vi.mock('@/api/auth', () => ({
  loginApi: vi.fn<(data: LoginParams) => Promise<LoginResult>>(),
  logoutApi: vi.fn<() => Promise<null>>(),
  getProfileApi: vi.fn<() => Promise<UserInfo>>(),
}))

import { loginApi, logoutApi, getProfileApi } from '@/api/auth'
import type { LoginParams, LoginResult, UserInfo } from '@/types/api'

const mockUserInfo: UserInfo = { id: 1, username: 'admin', nickname: '管理员' }

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

  it('login 成功：写入 token 与用户信息', async () => {
    vi.mocked(loginApi).mockResolvedValue({ access_token: 'jwt-abc' })
    vi.mocked(getProfileApi).mockResolvedValue(mockUserInfo)

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(loginApi).toHaveBeenCalledWith({ username: 'admin', password: '123456' })
    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toEqual(mockUserInfo)
    expect(userStore.isLoggedIn).toBe(true)
  })

  it('login 时拉取用户信息失败不阻塞登录', async () => {
    vi.mocked(loginApi).mockResolvedValue({ access_token: 'jwt-abc' })
    vi.mocked(getProfileApi).mockRejectedValue(new Error('profile failed'))

    const userStore = useUserStore()
    await userStore.login({ username: 'admin', password: '123456' })

    expect(userStore.token).toBe('jwt-abc')
    expect(userStore.userInfo).toBeNull()
  })

  it('login 接口失败：保持未登录状态', async () => {
    vi.mocked(loginApi).mockRejectedValue(new Error('用户名或密码错误'))

    const userStore = useUserStore()

    await expect(userStore.login({ username: 'admin', password: 'bad' })).rejects.toThrow(
      '用户名或密码错误',
    )
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })

  it('logout：调用接口后清空登录态', async () => {
    vi.mocked(logoutApi).mockResolvedValue(null)

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'
    userStore.userInfo = mockUserInfo

    await userStore.logout()

    expect(logoutApi).toHaveBeenCalled()
    expect(userStore.token).toBe('')
    expect(userStore.userInfo).toBeNull()
  })

  it('logout：接口失败也强制清空本地登录态', async () => {
    vi.mocked(logoutApi).mockRejectedValue(new Error('network down'))

    const userStore = useUserStore()
    userStore.token = 'jwt-abc'

    await expect(userStore.logout()).rejects.toThrow('network down')
    expect(userStore.token).toBe('')
    expect(userStore.isLoggedIn).toBe(false)
  })
})
