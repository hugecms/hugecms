import { post, get, put } from '@/utils/request'
import type { IShippingAreaQueryRequest, IShippingAreaQueryResponse, IShippingAreaCreateRequest, IShippingAreaResponse, IShippingAreaUpdateRequest, IShippingAreaDestroyRequest, IShippingAreaDestroyResponse } from '@/types/shippingArea'

/** [配送区域模块] 查询配送区域列表接口 */
export function shippingAreaSearchApi(page: number, pageSize: number, data: IShippingAreaQueryRequest): Promise<IShippingAreaQueryResponse> {
  return post<IShippingAreaQueryResponse>('admin/shippingArea/search', data, { params: { page, pageSize } })
}

/** [配送区域模块] 新增配送区域接口 */
export function shippingAreaStoreApi(data: IShippingAreaCreateRequest): Promise<IShippingAreaResponse> {
  return post<IShippingAreaResponse>('admin/shippingArea/store', data)
}

/** [配送区域模块] 获取配送区域详情接口 */
export function shippingAreaShowApi(id: number): Promise<IShippingAreaResponse> {
  return get<IShippingAreaResponse>('admin/shippingArea/show', { params: { id } })
}

/** [配送区域模块] 更新配送区域接口 */
export function shippingAreaUpdateApi(id: number, data: IShippingAreaUpdateRequest): Promise<IShippingAreaResponse> {
  return put<IShippingAreaResponse>('admin/shippingArea/update', data, { params: { id } })
}

/** [配送区域模块] 删除配送区域接口 */
export function shippingAreaDestroyApi(data: IShippingAreaDestroyRequest): Promise<IShippingAreaDestroyResponse> {
  return post<IShippingAreaDestroyResponse>('admin/shippingArea/destroy', data)
}
