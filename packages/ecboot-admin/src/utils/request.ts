import axios, { AxiosError, type AxiosRequestConfig, type AxiosResponse } from 'axios'
import { ElMessage } from 'element-plus'
// ElMessage 是函数式调用，不经模板编译，unplugin 无法按需注入样式，需显式引入（一次引入全局生效）
import 'element-plus/es/components/message/style/css'
import { useUserStore } from '@/stores/user'
import { LOGIN_PATH } from '@/consts/routes'
import type { ApiResponse } from '@/types/api'

const REQUEST_TIMEOUT = 10_000

/**
 * 业务错误：后端信封 code !== 0 时 reject 的类型（HTTP 层错误仍以 AxiosError reject）。
 * 错误提示已由拦截器统一弹出，调用方 catch 后勿重复 ElMessage。
 */
export class BusinessError extends Error {
  constructor(
    readonly code: number,
    message: string,
  ) {
    super(message)
    this.name = 'BusinessError'
  }
}

export const request = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  timeout: REQUEST_TIMEOUT,
})

request.interceptors.request.use((config) => {
  const userStore = useUserStore()
  if (userStore.token) {
    config.headers.Authorization = `Bearer ${userStore.token}`
  }
  return config
})

/**
 * 未授权（token 失效/缺失）：清空登录态。
 * 不在登录页时跳转登录页并携带回跳地址（整页刷新以彻底重置状态）；
 * 已在登录页（如登录接口本身返回 401，即凭据错误）不重复跳转，但必须给出错误反馈。
 */
function handleUnauthorized(message: string): void {
  useUserStore().reset()
  const { pathname, search } = window.location
  // location 域：登录页完整地址须含部署 base；BASE_URL 惰性读取以便测试按用例注入
  const base = import.meta.env.BASE_URL
  const loginHref = `${base}${LOGIN_PATH.slice(1)}`
  if (pathname === loginHref) {
    ElMessage.error(message)
    return
  }
  // 剥离部署 base 得到 router 路径（保留前导 /），redirect 由 LoginView 经 router.replace 回跳
  const routePath = pathname.startsWith(base) ? `/${pathname.slice(base.length)}` : pathname
  window.location.href = `${loginHref}?redirect=${encodeURIComponent(routePath + search)}`
}

/** HTTP 层错误分类文案 */
function getHttpErrorMessage(error: AxiosError): string {
  if (error.code === 'ECONNABORTED') {
    return '请求超时，请稍后重试'
  }
  if (!error.response) {
    return '网络异常，请检查网络连接'
  }
  const { status } = error.response
  if (status >= 500) {
    return '服务器开小差了，请稍后重试'
  }
  if (status === 404) {
    return '请求的资源不存在'
  }
  return `请求失败（${status}）`
}

request.interceptors.response.use(
  (response) => {
    const res = response.data as ApiResponse
    if (res.code === 0) {
      // 解包信封：直接返回业务数据。拦截器签名要求 AxiosResponse，
      // 故意绕过（运行时就是业务数据），调用方经下方泛型封装按 T 消费
      return res.data as AxiosResponse
    }
    if (res.code === 401) {
      handleUnauthorized(res.message || '登录已过期')
    } else {
      ElMessage.error(res.message || '请求失败')
    }
    return Promise.reject(new BusinessError(res.code, res.message || '请求失败'))
  },
  (error: AxiosError) => {
    // 主动取消（如组件卸载中断请求）不是异常，静默 reject 不打扰用户
    if (error.code === 'ERR_CANCELED') {
      return Promise.reject(error)
    }
    if (error.response?.status === 401) {
      const data = error.response.data as { message?: string } | undefined
      handleUnauthorized(data?.message || '登录已过期')
    } else {
      ElMessage.error(getHttpErrorMessage(error))
    }
    return Promise.reject(error instanceof Error ? error : new Error(String(error)))
  },
)

export function get<T>(url: string, config?: AxiosRequestConfig): Promise<T> {
  return request.get(url, config) as Promise<T>
}

export function post<T>(url: string, data?: unknown, config?: AxiosRequestConfig): Promise<T> {
  return request.post(url, data, config) as Promise<T>
}

export function put<T>(url: string, data?: unknown, config?: AxiosRequestConfig): Promise<T> {
  return request.put(url, data, config) as Promise<T>
}

export function del<T>(url: string, config?: AxiosRequestConfig): Promise<T> {
  return request.delete(url, config) as Promise<T>
}
