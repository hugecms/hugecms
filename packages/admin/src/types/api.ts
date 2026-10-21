/** 后端统一响应信封（laravel-foundation JsonResponses 约定，code === 0 表示成功） */
export interface ApiResponse<T = unknown> {
  code: number
  message: string
  data: T
}

/** 管理员用户信息 */
export interface UserInfo {
  id: number
  username: string
  nickname: string
}

/** 登录参数 */
export interface LoginParams {
  username: string
  password: string
}

/**
 * 登录结果。
 * 待定项：后端登录接口未实现，按 Laravel JWT 惯例假定字段名；
 * 联调如有出入只需改本文件、api/auth.ts 与 stores/user.ts。
 */
export interface LoginResult {
  access_token: string
}
