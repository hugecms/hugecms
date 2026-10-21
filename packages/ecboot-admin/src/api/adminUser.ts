import { post, get, put } from '@/utils/request'
import type { IAdminUserQueryRequest, IAdminUserQueryResponse, IAdminUserCreateRequest, IAdminUserResponse, IAdminUserUpdateRequest, IAdminUserDestroyRequest, IAdminUserDestroyResponse } from '@/types/adminUser'

/** [后台管理员账户模块] 查询后台管理员账户列表接口 */
export function adminUserSearchApi(page: number, pageSize: number, data: IAdminUserQueryRequest): Promise<IAdminUserQueryResponse> {
  return post<IAdminUserQueryResponse>('admin/adminUser/search', data, { params: { page, pageSize } })
}

/** [后台管理员账户模块] 新增后台管理员账户接口 */
export function adminUserStoreApi(data: IAdminUserCreateRequest): Promise<IAdminUserResponse> {
  return post<IAdminUserResponse>('admin/adminUser/store', data)
}

/** [后台管理员账户模块] 获取后台管理员账户详情接口 */
export function adminUserShowApi(id: number): Promise<IAdminUserResponse> {
  return get<IAdminUserResponse>('admin/adminUser/show', { params: { id } })
}

/** [后台管理员账户模块] 更新后台管理员账户接口 */
export function adminUserUpdateApi(id: number, data: IAdminUserUpdateRequest): Promise<IAdminUserResponse> {
  return put<IAdminUserResponse>('admin/adminUser/update', data, { params: { id } })
}

/** [后台管理员账户模块] 删除后台管理员账户接口 */
export function adminUserDestroyApi(data: IAdminUserDestroyRequest): Promise<IAdminUserDestroyResponse> {
  return post<IAdminUserDestroyResponse>('admin/adminUser/destroy', data)
}
