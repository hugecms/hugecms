import { post, get, put } from '@/utils/request'
import type { ISystemRegionQueryRequest, ISystemRegionQueryResponse, ISystemRegionCreateRequest, ISystemRegionResponse, ISystemRegionUpdateRequest, ISystemRegionDestroyRequest, ISystemRegionDestroyResponse } from '@/types/systemRegion'

/** [行政区划模块] 查询行政区划列表接口 */
export function systemRegionSearchApi(page: number, pageSize: number, data: ISystemRegionQueryRequest): Promise<ISystemRegionQueryResponse> {
  return post<ISystemRegionQueryResponse>('admin/systemRegion/search', data, { params: { page, pageSize } })
}

/** [行政区划模块] 新增行政区划接口 */
export function systemRegionStoreApi(data: ISystemRegionCreateRequest): Promise<ISystemRegionResponse> {
  return post<ISystemRegionResponse>('admin/systemRegion/store', data)
}

/** [行政区划模块] 获取行政区划详情接口 */
export function systemRegionShowApi(id: number): Promise<ISystemRegionResponse> {
  return get<ISystemRegionResponse>('admin/systemRegion/show', { params: { id } })
}

/** [行政区划模块] 更新行政区划接口 */
export function systemRegionUpdateApi(id: number, data: ISystemRegionUpdateRequest): Promise<ISystemRegionResponse> {
  return put<ISystemRegionResponse>('admin/systemRegion/update', data, { params: { id } })
}

/** [行政区划模块] 删除行政区划接口 */
export function systemRegionDestroyApi(data: ISystemRegionDestroyRequest): Promise<ISystemRegionDestroyResponse> {
  return post<ISystemRegionDestroyResponse>('admin/systemRegion/destroy', data)
}
