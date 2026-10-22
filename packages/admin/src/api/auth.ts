import { get, post } from '@/utils/request'
import type { IAuthChangePasswordRequest, IAuthLoginRequest, IAuthLoginResponse, IAuthMeResponse } from '@/types/auth'

/** [认证模块] 管理员登录接口 */
export function authLoginApi(data: IAuthLoginRequest): Promise<IAuthLoginResponse> {
  return post<IAuthLoginResponse>('admin/auth/login', data)
}

/** [认证模块] 获取当前管理员信息 */
export function authMeApi(): Promise<IAuthMeResponse> {
  return get<IAuthMeResponse>('admin/auth/me')
}

/** [认证模块] 管理员退出登录 */
export function authLogoutApi(): Promise<null> {
  return post<null>('admin/auth/logout')
}

/** [认证模块] 修改当前管理员密码 */
export function authChangePasswordApi(data: IAuthChangePasswordRequest): Promise<null> {
  return post<null>('admin/auth/changePassword', data)
}
