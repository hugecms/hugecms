import { post } from '@/utils/request'
import type { IAuthLoginRequest, IAuthLoginResponse } from '@/types/auth'

/** [认证模块] 管理员登录接口 */
export function authLoginApi(data: IAuthLoginRequest): Promise<IAuthLoginResponse> {
  return post<IAuthLoginResponse>('admin/auth/login', data)
}
