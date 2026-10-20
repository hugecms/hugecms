import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { AxiosError } from 'axios'
import { createPinia, setActivePinia } from 'pinia'
import { del, get, post } from '@/utils/request'
import { useUserStore } from '@/stores/user'

vi.mock('element-plus', () => ({
  ElMessage: { error: vi.fn(), success: vi.fn() },
}))

import { ElMessage } from 'element-plus'

/** 短路网络层：让 axios 实例直接返回构造的响应 */
function useMockAdapter(payload: unknown, status = 200) {
  return vi.fn(async (config: Record<string, unknown>) => ({
    data: payload,
    status,
    statusText: 'OK',
    headers: {},
    config,
  }))
}

describe('request 拦截器', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.stubGlobal('location', { pathname: '/dashboard', search: '', href: '' })
  })

  afterEach(() => {
    vi.unstubAllGlobals()
    vi.clearAllMocks()
  })

  it('请求头注入 Bearer token', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const userStore = useUserStore()
    userStore.token = 'jwt-token'

    await get('/admin/auth/profile')

    expect(adapter).toHaveBeenCalledTimes(1)
    const config = adapter.mock.calls[0]?.[0] as { headers: Record<string, string> }
    expect(config.headers.Authorization).toBe('Bearer jwt-token')
  })

  it('无 token 时不注入 Authorization', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await get('/anything')

    const config = adapter.mock.calls[0]?.[0] as { headers: Record<string, string> }
    expect(config.headers.Authorization).toBeUndefined()
  })

  it('code=0 时解包返回 data', async () => {
    const adapter = useMockAdapter({ code: 0, message: 'ok', data: { id: 1, name: 'x' } })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const data = await post<{ id: number; name: string }>('/anything', { a: 1 })

    expect(data).toEqual({ id: 1, name: 'x' })
    expect(ElMessage.error).not.toHaveBeenCalled()
  })

  it('code 非 0 时 reject 并弹出错误信息', async () => {
    const adapter = useMockAdapter({ code: 50001, message: '用户名或密码错误', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await expect(post('/admin/auth/login', {})).rejects.toThrow('用户名或密码错误')
    expect(ElMessage.error).toHaveBeenCalledWith('用户名或密码错误')
  })

  it('业务码 401 时清空登录态并跳转登录页（带 redirect）', async () => {
    const adapter = useMockAdapter({ code: 401, message: '登录已过期', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    const userStore = useUserStore()
    userStore.token = 'expired-token'

    await expect(get('/admin/auth/profile')).rejects.toThrow()

    expect(userStore.token).toBe('')
    expect(window.location.href).toBe(`/login?redirect=${encodeURIComponent('/dashboard')}`)
  })

  it('HTTP 401 同样清空登录态并跳转', async () => {
    // 自定义 adapter resolve 不会经过 axios 的 settle（非 2xx 转 reject 是内置 adapter 的职责），
    // 因此直接 reject 携带 response 的 AxiosError，与浏览器 xhr adapter 的真实行为一致
    const { request } = await import('@/utils/request')
    request.defaults.adapter = (async () => {
      throw new AxiosError(
        'Request failed with status code 401',
        AxiosError.ERR_BAD_REQUEST,
        {} as never,
        {} as never,
        {
          data: { code: 401, message: 'Unauthorized', data: null },
          status: 401,
          statusText: 'Unauthorized',
          headers: {},
          config: {} as never,
        },
      )
    }) as never

    const userStore = useUserStore()
    userStore.token = 'expired-token'

    await expect(get('/admin/auth/profile')).rejects.toThrow()

    expect(userStore.token).toBe('')
    expect(window.location.href).toBe(`/login?redirect=${encodeURIComponent('/dashboard')}`)
  })

  it('已在登录页时 401 不重复跳转', async () => {
    vi.stubGlobal('location', { pathname: '/login', search: '', href: '' })
    const adapter = useMockAdapter({ code: 401, message: '登录已过期', data: null })
    const { request } = await import('@/utils/request')
    request.defaults.adapter = adapter as never

    await expect(get('/anything')).rejects.toThrow()

    expect(window.location.href).toBe('')
  })

  it('HTTP 500 弹出服务端错误文案', async () => {
    const { request } = await import('@/utils/request')
    request.defaults.adapter = (async () => {
      throw new AxiosError(
        'Request failed with status code 500',
        AxiosError.ERR_BAD_RESPONSE,
        {} as never,
        {} as never,
        {
          data: { message: 'server error' },
          status: 500,
          statusText: 'Internal Server Error',
          headers: {},
          config: {} as never,
        },
      )
    }) as never

    await expect(get('/anything')).rejects.toThrow()
    expect(ElMessage.error).toHaveBeenCalledWith('服务器开小差了，请稍后重试')
  })

  it('网络异常（无 response）弹出网络错误文案', async () => {
    const { request } = await import('@/utils/request')
    request.defaults.adapter = (async () => {
      throw new AxiosError('Network Error')
    }) as never

    await expect(del('/anything')).rejects.toThrow()
    expect(ElMessage.error).toHaveBeenCalledWith('网络异常，请检查网络连接')
  })
})
