import { post, get, put } from '@/utils/request'
import type { IAdminRolePermissionQueryRequest, IAdminRolePermissionQueryResponse, IAdminRolePermissionCreateRequest, IAdminRolePermissionResponse, IAdminRolePermissionUpdateRequest, IAdminRolePermissionDestroyRequest, IAdminRolePermissionDestroyResponse } from '@/types/adminRolePermission'

/** [角色权限关联模块] 查询角色权限关联列表接口 */
export function adminRolePermissionSearchApi(page: number, pageSize: number, data: IAdminRolePermissionQueryRequest): Promise<IAdminRolePermissionQueryResponse> {
  return post<IAdminRolePermissionQueryResponse>('admin/adminRolePermission/search', data, { params: { page, pageSize } })
}

/** [角色权限关联模块] 新增角色权限关联接口 */
export function adminRolePermissionStoreApi(data: IAdminRolePermissionCreateRequest): Promise<IAdminRolePermissionResponse> {
  return post<IAdminRolePermissionResponse>('admin/adminRolePermission/store', data)
}

/** [角色权限关联模块] 获取角色权限关联详情接口 */
export function adminRolePermissionShowApi(id: number): Promise<IAdminRolePermissionResponse> {
  return get<IAdminRolePermissionResponse>('admin/adminRolePermission/show', { params: { id } })
}

/** [角色权限关联模块] 更新角色权限关联接口 */
export function adminRolePermissionUpdateApi(id: number, data: IAdminRolePermissionUpdateRequest): Promise<IAdminRolePermissionResponse> {
  return put<IAdminRolePermissionResponse>('admin/adminRolePermission/update', data, { params: { id } })
}

/** [角色权限关联模块] 删除角色权限关联接口 */
export function adminRolePermissionDestroyApi(data: IAdminRolePermissionDestroyRequest): Promise<IAdminRolePermissionDestroyResponse> {
  return post<IAdminRolePermissionDestroyResponse>('admin/adminRolePermission/destroy', data)
}
