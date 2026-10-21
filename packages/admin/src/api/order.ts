import { post, get, put } from '@/utils/request'
import type { IOrderQueryRequest, IOrderQueryResponse, IOrderCreateRequest, IOrderResponse, IOrderUpdateRequest, IOrderDestroyRequest, IOrderDestroyResponse } from '@/types/order'

/** [订单模块] 查询订单列表接口 */
export function orderSearchApi(page: number, pageSize: number, data: IOrderQueryRequest): Promise<IOrderQueryResponse> {
  return post<IOrderQueryResponse>('admin/order/search', data, { params: { page, pageSize } })
}

/** [订单模块] 新增订单接口 */
export function orderStoreApi(data: IOrderCreateRequest): Promise<IOrderResponse> {
  return post<IOrderResponse>('admin/order/store', data)
}

/** [订单模块] 获取订单详情接口 */
export function orderShowApi(id: number): Promise<IOrderResponse> {
  return get<IOrderResponse>('admin/order/show', { params: { id } })
}

/** [订单模块] 更新订单接口 */
export function orderUpdateApi(id: number, data: IOrderUpdateRequest): Promise<IOrderResponse> {
  return put<IOrderResponse>('admin/order/update', data, { params: { id } })
}

/** [订单模块] 删除订单接口 */
export function orderDestroyApi(data: IOrderDestroyRequest): Promise<IOrderDestroyResponse> {
  return post<IOrderDestroyResponse>('admin/order/destroy', data)
}
