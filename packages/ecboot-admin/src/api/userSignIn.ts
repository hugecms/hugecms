import { post, get, put } from '@/utils/request'
import type { IUserSignInQueryRequest, IUserSignInQueryResponse, IUserSignInCreateRequest, IUserSignInResponse, IUserSignInUpdateRequest, IUserSignInDestroyRequest, IUserSignInDestroyResponse } from '@/types/userSignIn'

/** [用户签到模块] 查询用户签到列表接口 */
export function userSignInSearchApi(page: number, pageSize: number, data: IUserSignInQueryRequest): Promise<IUserSignInQueryResponse> {
  return post<IUserSignInQueryResponse>('admin/userSignIn/search', data, { params: { page, pageSize } })
}

/** [用户签到模块] 新增用户签到接口 */
export function userSignInStoreApi(data: IUserSignInCreateRequest): Promise<IUserSignInResponse> {
  return post<IUserSignInResponse>('admin/userSignIn/store', data)
}

/** [用户签到模块] 获取用户签到详情接口 */
export function userSignInShowApi(id: number): Promise<IUserSignInResponse> {
  return get<IUserSignInResponse>('admin/userSignIn/show', { params: { id } })
}

/** [用户签到模块] 更新用户签到接口 */
export function userSignInUpdateApi(id: number, data: IUserSignInUpdateRequest): Promise<IUserSignInResponse> {
  return put<IUserSignInResponse>('admin/userSignIn/update', data, { params: { id } })
}

/** [用户签到模块] 删除用户签到接口 */
export function userSignInDestroyApi(data: IUserSignInDestroyRequest): Promise<IUserSignInDestroyResponse> {
  return post<IUserSignInDestroyResponse>('admin/userSignIn/destroy', data)
}
