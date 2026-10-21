import { post, get, put } from '@/utils/request'
import type { IAdminUserRoleQueryRequest, IAdminUserRoleQueryResponse, IAdminUserRoleCreateRequest, IAdminUserRoleResponse, IAdminUserRoleUpdateRequest, IAdminUserRoleDestroyRequest, IAdminUserRoleDestroyResponse } from '@/types/adminUserRole'

/** [管理员角色关联模块] 查询管理员角色关联列表接口 */
export function adminUserRoleSearchApi(page: number, pageSize: number, data: IAdminUserRoleQueryRequest): Promise<IAdminUserRoleQueryResponse> {
  return post<IAdminUserRoleQueryResponse>('admin/adminUserRole/search', data, { params: { page, pageSize } })
}

/** [管理员角色关联模块] 新增管理员角色关联接口 */
export function adminUserRoleStoreApi(data: IAdminUserRoleCreateRequest): Promise<IAdminUserRoleResponse> {
  return post<IAdminUserRoleResponse>('admin/adminUserRole/store', data)
}

/** [管理员角色关联模块] 获取管理员角色关联详情接口 */
export function adminUserRoleShowApi(id: number): Promise<IAdminUserRoleResponse> {
  return get<IAdminUserRoleResponse>('admin/adminUserRole/show', { params: { id } })
}

/** [管理员角色关联模块] 更新管理员角色关联接口 */
export function adminUserRoleUpdateApi(id: number, data: IAdminUserRoleUpdateRequest): Promise<IAdminUserRoleResponse> {
  return put<IAdminUserRoleResponse>('admin/adminUserRole/update', data, { params: { id } })
}

/** [管理员角色关联模块] 删除管理员角色关联接口 */
export function adminUserRoleDestroyApi(data: IAdminUserRoleDestroyRequest): Promise<IAdminUserRoleDestroyResponse> {
  return post<IAdminUserRoleDestroyResponse>('admin/adminUserRole/destroy', data)
}
