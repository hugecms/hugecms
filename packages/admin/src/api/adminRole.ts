import { post, get, put } from '@/utils/request'
import type { IAdminRoleQueryRequest, IAdminRoleQueryResponse, IAdminRoleCreateRequest, IAdminRoleResponse, IAdminRoleUpdateRequest, IAdminRoleDestroyRequest, IAdminRoleDestroyResponse } from '@/types/adminRole'

/** [后台角色模块] 查询后台角色列表接口 */
export function adminRoleSearchApi(page: number, pageSize: number, data: IAdminRoleQueryRequest): Promise<IAdminRoleQueryResponse> {
  return post<IAdminRoleQueryResponse>('admin/adminRole/search', data, { params: { page, pageSize } })
}

/** [后台角色模块] 新增后台角色接口 */
export function adminRoleStoreApi(data: IAdminRoleCreateRequest): Promise<IAdminRoleResponse> {
  return post<IAdminRoleResponse>('admin/adminRole/store', data)
}

/** [后台角色模块] 获取后台角色详情接口 */
export function adminRoleShowApi(id: number): Promise<IAdminRoleResponse> {
  return get<IAdminRoleResponse>('admin/adminRole/show', { params: { id } })
}

/** [后台角色模块] 更新后台角色接口 */
export function adminRoleUpdateApi(id: number, data: IAdminRoleUpdateRequest): Promise<IAdminRoleResponse> {
  return put<IAdminRoleResponse>('admin/adminRole/update', data, { params: { id } })
}

/** [后台角色模块] 删除后台角色接口 */
export function adminRoleDestroyApi(data: IAdminRoleDestroyRequest): Promise<IAdminRoleDestroyResponse> {
  return post<IAdminRoleDestroyResponse>('admin/adminRole/destroy', data)
}
