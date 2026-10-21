import { post, get, put } from '@/utils/request'
import type { IUserAccountQueryRequest, IUserAccountQueryResponse, IUserAccountCreateRequest, IUserAccountResponse, IUserAccountUpdateRequest, IUserAccountDestroyRequest, IUserAccountDestroyResponse } from '@/types/userAccount'

/** [用户账户模块] 查询用户账户列表接口 */
export function userAccountSearchApi(page: number, pageSize: number, data: IUserAccountQueryRequest): Promise<IUserAccountQueryResponse> {
  return post<IUserAccountQueryResponse>('admin/userAccount/search', data, { params: { page, pageSize } })
}

/** [用户账户模块] 新增用户账户接口 */
export function userAccountStoreApi(data: IUserAccountCreateRequest): Promise<IUserAccountResponse> {
  return post<IUserAccountResponse>('admin/userAccount/store', data)
}

/** [用户账户模块] 获取用户账户详情接口 */
export function userAccountShowApi(id: number): Promise<IUserAccountResponse> {
  return get<IUserAccountResponse>('admin/userAccount/show', { params: { id } })
}

/** [用户账户模块] 更新用户账户接口 */
export function userAccountUpdateApi(id: number, data: IUserAccountUpdateRequest): Promise<IUserAccountResponse> {
  return put<IUserAccountResponse>('admin/userAccount/update', data, { params: { id } })
}

/** [用户账户模块] 删除用户账户接口 */
export function userAccountDestroyApi(data: IUserAccountDestroyRequest): Promise<IUserAccountDestroyResponse> {
  return post<IUserAccountDestroyResponse>('admin/userAccount/destroy', data)
}
