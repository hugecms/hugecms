export interface IAuthLoginRequest {
  user_name: string, // 用户用户名
  password: string, // 用户登录密码
  captcha?: string, // 登录图片验证码,V1 未启用
  captchaId?: string, // 图片验证码ID,V1 未启用
}

export interface IAuthLoginResponse {
  token: string, // token
  expires: number, // expires
}

export interface IAuthMeResponse {
  id: number, // 管理员ID
  username: string, // 用户名
  email: string | null, // 邮箱
  isSuper: number, // 超级管理员:0=否,1=是
}

export interface IAuthChangePasswordRequest {
  old_password: string, // 原密码
  new_password: string, // 新密码,至少6位
  new_password_confirmation: string, // 确认新密码
}
