export interface IAuthLoginRequest {
  user_name: string, // 用户用户名
  password: string, // 用户登录密码
  captcha: string, // 登录图片验证码
  captchaId: string, // 图片验证码ID
}

export interface IAuthLoginResponse {
  token: string, // token
  expires: number, // expires
}

