import axios from 'axios'
import type { AxiosResponse } from 'axios'
import { message } from 'antd'

export interface ApiResponse<T = any> {
  code: number
  message: string
  data: T
}

const instance = axios.create({
  baseURL: '/api/admin',
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
  },
})

// 请求拦截器：注入 JWT Token
instance.interceptors.request.use(
  (config) => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('hugecms_token')
      if (token && config.headers) {
        config.headers.Authorization = `Bearer ${token}`
      }
    }
    return config
  },
  (error) => Promise.reject(error)
)

// 响应拦截器：统一数据解包与状态处理
instance.interceptors.response.use(
  (response: AxiosResponse<ApiResponse>) => {
    const res = response.data
    // GoFrame 后台中间件统一返回结构: { code, message, data }
    if (res.code !== 0 && res.code !== 200) {
      // 401 或未认证跳转登录
      if (res.code === 401) {
        if (typeof window !== 'undefined') {
          localStorage.removeItem('hugecms_token')
          if (!window.location.pathname.includes('/login')) {
            window.location.href = '/admin/login'
          }
        }
      }
      message.error(res.message || '请求发生错误')
      return Promise.reject(new Error(res.message || 'Error'))
    }
    return res.data
  },
  (error) => {
    if (error.response) {
      if (error.response.status === 401) {
        if (typeof window !== 'undefined') {
          localStorage.removeItem('hugecms_token')
          if (!window.location.pathname.includes('/login')) {
            window.location.href = '/admin/login'
          }
        }
      }
      message.error(error.response.data?.message || `网络请求失败 (${error.response.status})`)
    } else {
      message.error(error.message || '网络连接异常，请检查接口服务')
    }
    return Promise.reject(error)
  }
)

export default instance
