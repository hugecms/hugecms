import { post, get, put } from '@/utils/request'
import type { IOrderItemQueryRequest, IOrderItemQueryResponse, IOrderItemCreateRequest, IOrderItemResponse, IOrderItemUpdateRequest, IOrderItemDestroyRequest, IOrderItemDestroyResponse } from '@/types/orderItem'

/** [订单明细模块] 查询订单明细列表接口 */
export function orderItemSearchApi(page: number, pageSize: number, data: IOrderItemQueryRequest): Promise<IOrderItemQueryResponse> {
  return post<IOrderItemQueryResponse>('admin/orderItem/search', data, { params: { page, pageSize } })
}

/** [订单明细模块] 新增订单明细接口 */
export function orderItemStoreApi(data: IOrderItemCreateRequest): Promise<IOrderItemResponse> {
  return post<IOrderItemResponse>('admin/orderItem/store', data)
}

/** [订单明细模块] 获取订单明细详情接口 */
export function orderItemShowApi(id: number): Promise<IOrderItemResponse> {
  return get<IOrderItemResponse>('admin/orderItem/show', { params: { id } })
}

/** [订单明细模块] 更新订单明细接口 */
export function orderItemUpdateApi(id: number, data: IOrderItemUpdateRequest): Promise<IOrderItemResponse> {
  return put<IOrderItemResponse>('admin/orderItem/update', data, { params: { id } })
}

/** [订单明细模块] 删除订单明细接口 */
export function orderItemDestroyApi(data: IOrderItemDestroyRequest): Promise<IOrderItemDestroyResponse> {
  return post<IOrderItemDestroyResponse>('admin/orderItem/destroy', data)
}
