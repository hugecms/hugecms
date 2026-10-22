/** 后端统一响应信封（laravel-foundation JsonResponses 约定，code === 0 表示成功） */
export interface ApiResponse<T = unknown> {
  code: number
  message: string
  data: T
}

/** 管理员用户信息（来自 /auth/me） */
export interface UserInfo {
  id: number
  username: string
  email?: string | null
  isSuper?: number
}

/** 登录参数 */
export interface LoginParams {
  username: string
  password: string
}
