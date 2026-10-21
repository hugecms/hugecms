import { post, get, put } from '@/utils/request'
import type { IUserSmsCodeQueryRequest, IUserSmsCodeQueryResponse, IUserSmsCodeCreateRequest, IUserSmsCodeResponse, IUserSmsCodeUpdateRequest, IUserSmsCodeDestroyRequest, IUserSmsCodeDestroyResponse } from '@/types/userSmsCode'

/** [短信验证码模块] 查询短信验证码列表接口 */
export function userSmsCodeSearchApi(page: number, pageSize: number, data: IUserSmsCodeQueryRequest): Promise<IUserSmsCodeQueryResponse> {
  return post<IUserSmsCodeQueryResponse>('admin/userSmsCode/search', data, { params: { page, pageSize } })
}

/** [短信验证码模块] 新增短信验证码接口 */
export function userSmsCodeStoreApi(data: IUserSmsCodeCreateRequest): Promise<IUserSmsCodeResponse> {
  return post<IUserSmsCodeResponse>('admin/userSmsCode/store', data)
}

/** [短信验证码模块] 获取短信验证码详情接口 */
export function userSmsCodeShowApi(id: number): Promise<IUserSmsCodeResponse> {
  return get<IUserSmsCodeResponse>('admin/userSmsCode/show', { params: { id } })
}

/** [短信验证码模块] 更新短信验证码接口 */
export function userSmsCodeUpdateApi(id: number, data: IUserSmsCodeUpdateRequest): Promise<IUserSmsCodeResponse> {
  return put<IUserSmsCodeResponse>('admin/userSmsCode/update', data, { params: { id } })
}

/** [短信验证码模块] 删除短信验证码接口 */
export function userSmsCodeDestroyApi(data: IUserSmsCodeDestroyRequest): Promise<IUserSmsCodeDestroyResponse> {
  return post<IUserSmsCodeDestroyResponse>('admin/userSmsCode/destroy', data)
}
