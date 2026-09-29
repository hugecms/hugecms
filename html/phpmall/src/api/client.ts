import axios, { type AxiosRequestConfig, type AxiosResponse } from 'axios'

/**
 * 统一 API 响应包装模型
 */
export interface ApiResponse<T = any> {
  code: number
  message: string
  data: T
  success: boolean
}

/**
 * 全局 Axios 实例
 */
export const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
  },
})

// 请求拦截器
apiClient.interceptors.request.use(
  (config) => {
    // 注入用户或商家 Token
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('mall_auth_token')
      if (token && config.headers) {
        config.headers.Authorization = `Bearer ${token}`
      }
    }
    return config
  },
  (error) => Promise.reject(error),
)

// 响应拦截器
apiClient.interceptors.response.use(
  (response: AxiosResponse<ApiResponse>) => {
    const res = response.data
    // 如果后端直接返回标准结构且 code 非 200，按业务异常抛出
    if (res && typeof res.code === 'number' && res.code !== 200 && res.code !== 0) {
      return Promise.reject(new Error(res.message || '请求失败'))
    }
    return response
  },
  (error) => {
    // 统一处理 401 登录失效、500 服务端异常等
    const msg = error.response?.data?.message || error.message || '网络连接异常'
    return Promise.reject(new Error(msg))
  },
)

/**
 * 便捷请求封装
 */
export async function request<T>(config: AxiosRequestConfig): Promise<T> {
  const response = await apiClient.request<ApiResponse<T> | T>(config)
  const data = response.data as any
  // 如果封装了 ApiResponse 则解包 data，否则直接返回
  if (data && typeof data === 'object' && 'data' in data && 'code' in data) {
    return data.data as T
  }
  return data as T
}
