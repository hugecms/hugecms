import { post, get, put } from '@/utils/request'
import type { IAdminPermissionQueryRequest, IAdminPermissionQueryResponse, IAdminPermissionCreateRequest, IAdminPermissionResponse, IAdminPermissionUpdateRequest, IAdminPermissionDestroyRequest, IAdminPermissionDestroyResponse } from '@/types/adminPermission'

/** [后台权限模块] 查询后台权限列表接口 */
export function adminPermissionSearchApi(page: number, pageSize: number, data: IAdminPermissionQueryRequest): Promise<IAdminPermissionQueryResponse> {
  return post<IAdminPermissionQueryResponse>('admin/adminPermission/search', data, { params: { page, pageSize } })
}

/** [后台权限模块] 新增后台权限接口 */
export function adminPermissionStoreApi(data: IAdminPermissionCreateRequest): Promise<IAdminPermissionResponse> {
  return post<IAdminPermissionResponse>('admin/adminPermission/store', data)
}

/** [后台权限模块] 获取后台权限详情接口 */
export function adminPermissionShowApi(id: number): Promise<IAdminPermissionResponse> {
  return get<IAdminPermissionResponse>('admin/adminPermission/show', { params: { id } })
}

/** [后台权限模块] 更新后台权限接口 */
export function adminPermissionUpdateApi(id: number, data: IAdminPermissionUpdateRequest): Promise<IAdminPermissionResponse> {
  return put<IAdminPermissionResponse>('admin/adminPermission/update', data, { params: { id } })
}

/** [后台权限模块] 删除后台权限接口 */
export function adminPermissionDestroyApi(data: IAdminPermissionDestroyRequest): Promise<IAdminPermissionDestroyResponse> {
  return post<IAdminPermissionDestroyResponse>('admin/adminPermission/destroy', data)
}
