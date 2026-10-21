import { post, get, put } from '@/utils/request'
import type { IUserProfileQueryRequest, IUserProfileQueryResponse, IUserProfileCreateRequest, IUserProfileResponse, IUserProfileUpdateRequest, IUserProfileDestroyRequest, IUserProfileDestroyResponse } from '@/types/userProfile'

/** [用户资料模块] 查询用户资料列表接口 */
export function userProfileSearchApi(page: number, pageSize: number, data: IUserProfileQueryRequest): Promise<IUserProfileQueryResponse> {
  return post<IUserProfileQueryResponse>('admin/userProfile/search', data, { params: { page, pageSize } })
}

/** [用户资料模块] 新增用户资料接口 */
export function userProfileStoreApi(data: IUserProfileCreateRequest): Promise<IUserProfileResponse> {
  return post<IUserProfileResponse>('admin/userProfile/store', data)
}

/** [用户资料模块] 获取用户资料详情接口 */
export function userProfileShowApi(id: number): Promise<IUserProfileResponse> {
  return get<IUserProfileResponse>('admin/userProfile/show', { params: { id } })
}

/** [用户资料模块] 更新用户资料接口 */
export function userProfileUpdateApi(id: number, data: IUserProfileUpdateRequest): Promise<IUserProfileResponse> {
  return put<IUserProfileResponse>('admin/userProfile/update', data, { params: { id } })
}

/** [用户资料模块] 删除用户资料接口 */
export function userProfileDestroyApi(data: IUserProfileDestroyRequest): Promise<IUserProfileDestroyResponse> {
  return post<IUserProfileDestroyResponse>('admin/userProfile/destroy', data)
}
