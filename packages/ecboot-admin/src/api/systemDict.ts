import { post, get, put } from '@/utils/request'
import type { ISystemDictQueryRequest, ISystemDictQueryResponse, ISystemDictCreateRequest, ISystemDictResponse, ISystemDictUpdateRequest, ISystemDictDestroyRequest, ISystemDictDestroyResponse } from '@/types/systemDict'

/** [字典类型模块] 查询字典类型列表接口 */
export function systemDictSearchApi(page: number, pageSize: number, data: ISystemDictQueryRequest): Promise<ISystemDictQueryResponse> {
  return post<ISystemDictQueryResponse>('admin/systemDict/search', data, { params: { page, pageSize } })
}

/** [字典类型模块] 新增字典类型接口 */
export function systemDictStoreApi(data: ISystemDictCreateRequest): Promise<ISystemDictResponse> {
  return post<ISystemDictResponse>('admin/systemDict/store', data)
}

/** [字典类型模块] 获取字典类型详情接口 */
export function systemDictShowApi(id: number): Promise<ISystemDictResponse> {
  return get<ISystemDictResponse>('admin/systemDict/show', { params: { id } })
}

/** [字典类型模块] 更新字典类型接口 */
export function systemDictUpdateApi(id: number, data: ISystemDictUpdateRequest): Promise<ISystemDictResponse> {
  return put<ISystemDictResponse>('admin/systemDict/update', data, { params: { id } })
}

/** [字典类型模块] 删除字典类型接口 */
export function systemDictDestroyApi(data: ISystemDictDestroyRequest): Promise<ISystemDictDestroyResponse> {
  return post<ISystemDictDestroyResponse>('admin/systemDict/destroy', data)
}
