import { get, post } from '@/utils/request'
import type { LoginParams, LoginResult, UserInfo } from '@/types/api'

/** 登录 */
export function loginApi(data: LoginParams): Promise<LoginResult> {
  return post<LoginResult>('/admin/auth/login', data)
}

/** 退出登录 */
export function logoutApi(): Promise<null> {
  return post<null>('/admin/auth/logout')
}

/** 当前登录用户信息 */
export function getProfileApi(): Promise<UserInfo> {
  return get<UserInfo>('/admin/auth/profile')
}
