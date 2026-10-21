import { post, get, put } from '@/utils/request'
import type { IShippingMethodQueryRequest, IShippingMethodQueryResponse, IShippingMethodCreateRequest, IShippingMethodResponse, IShippingMethodUpdateRequest, IShippingMethodDestroyRequest, IShippingMethodDestroyResponse } from '@/types/shippingMethod'

/** [配送方式模块] 查询配送方式列表接口 */
export function shippingMethodSearchApi(page: number, pageSize: number, data: IShippingMethodQueryRequest): Promise<IShippingMethodQueryResponse> {
  return post<IShippingMethodQueryResponse>('admin/shippingMethod/search', data, { params: { page, pageSize } })
}

/** [配送方式模块] 新增配送方式接口 */
export function shippingMethodStoreApi(data: IShippingMethodCreateRequest): Promise<IShippingMethodResponse> {
  return post<IShippingMethodResponse>('admin/shippingMethod/store', data)
}

/** [配送方式模块] 获取配送方式详情接口 */
export function shippingMethodShowApi(id: number): Promise<IShippingMethodResponse> {
  return get<IShippingMethodResponse>('admin/shippingMethod/show', { params: { id } })
}

/** [配送方式模块] 更新配送方式接口 */
export function shippingMethodUpdateApi(id: number, data: IShippingMethodUpdateRequest): Promise<IShippingMethodResponse> {
  return put<IShippingMethodResponse>('admin/shippingMethod/update', data, { params: { id } })
}

/** [配送方式模块] 删除配送方式接口 */
export function shippingMethodDestroyApi(data: IShippingMethodDestroyRequest): Promise<IShippingMethodDestroyResponse> {
  return post<IShippingMethodDestroyResponse>('admin/shippingMethod/destroy', data)
}
