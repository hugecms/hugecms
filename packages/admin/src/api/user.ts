import { post, get, put } from '@/utils/request'
import type { IUserQueryRequest, IUserQueryResponse, IUserCreateRequest, IUserResponse, IUserUpdateRequest, IUserDestroyRequest, IUserDestroyResponse } from '@/types/user'

/** [用户模块] 查询用户列表接口 */
export function userSearchApi(page: number, pageSize: number, data: IUserQueryRequest): Promise<IUserQueryResponse> {
  return post<IUserQueryResponse>('admin/user/search', data, { params: { page, pageSize } })
}

/** [用户模块] 新增用户接口 */
export function userStoreApi(data: IUserCreateRequest): Promise<IUserResponse> {
  return post<IUserResponse>('admin/user/store', data)
}

/** [用户模块] 获取用户详情接口 */
export function userShowApi(id: number): Promise<IUserResponse> {
  return get<IUserResponse>('admin/user/show', { params: { id } })
}

/** [用户模块] 更新用户接口 */
export function userUpdateApi(id: number, data: IUserUpdateRequest): Promise<IUserResponse> {
  return put<IUserResponse>('admin/user/update', data, { params: { id } })
}

/** [用户模块] 删除用户接口 */
export function userDestroyApi(data: IUserDestroyRequest): Promise<IUserDestroyResponse> {
  return post<IUserDestroyResponse>('admin/user/destroy', data)
}
