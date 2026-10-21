import { post, get, put } from '@/utils/request'
import type { ISystemDictItemQueryRequest, ISystemDictItemQueryResponse, ISystemDictItemCreateRequest, ISystemDictItemResponse, ISystemDictItemUpdateRequest, ISystemDictItemDestroyRequest, ISystemDictItemDestroyResponse } from '@/types/systemDictItem'

/** [字典项模块] 查询字典项列表接口 */
export function systemDictItemSearchApi(page: number, pageSize: number, data: ISystemDictItemQueryRequest): Promise<ISystemDictItemQueryResponse> {
  return post<ISystemDictItemQueryResponse>('admin/systemDictItem/search', data, { params: { page, pageSize } })
}

/** [字典项模块] 新增字典项接口 */
export function systemDictItemStoreApi(data: ISystemDictItemCreateRequest): Promise<ISystemDictItemResponse> {
  return post<ISystemDictItemResponse>('admin/systemDictItem/store', data)
}

/** [字典项模块] 获取字典项详情接口 */
export function systemDictItemShowApi(id: number): Promise<ISystemDictItemResponse> {
  return get<ISystemDictItemResponse>('admin/systemDictItem/show', { params: { id } })
}

/** [字典项模块] 更新字典项接口 */
export function systemDictItemUpdateApi(id: number, data: ISystemDictItemUpdateRequest): Promise<ISystemDictItemResponse> {
  return put<ISystemDictItemResponse>('admin/systemDictItem/update', data, { params: { id } })
}

/** [字典项模块] 删除字典项接口 */
export function systemDictItemDestroyApi(data: ISystemDictItemDestroyRequest): Promise<ISystemDictItemDestroyResponse> {
  return post<ISystemDictItemDestroyResponse>('admin/systemDictItem/destroy', data)
}
