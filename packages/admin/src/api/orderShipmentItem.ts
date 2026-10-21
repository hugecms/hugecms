import { post, get, put } from '@/utils/request'
import type { IOrderShipmentItemQueryRequest, IOrderShipmentItemQueryResponse, IOrderShipmentItemCreateRequest, IOrderShipmentItemResponse, IOrderShipmentItemUpdateRequest, IOrderShipmentItemDestroyRequest, IOrderShipmentItemDestroyResponse } from '@/types/orderShipmentItem'

/** [发货单明细模块] 查询发货单明细列表接口 */
export function orderShipmentItemSearchApi(page: number, pageSize: number, data: IOrderShipmentItemQueryRequest): Promise<IOrderShipmentItemQueryResponse> {
  return post<IOrderShipmentItemQueryResponse>('admin/orderShipmentItem/search', data, { params: { page, pageSize } })
}

/** [发货单明细模块] 新增发货单明细接口 */
export function orderShipmentItemStoreApi(data: IOrderShipmentItemCreateRequest): Promise<IOrderShipmentItemResponse> {
  return post<IOrderShipmentItemResponse>('admin/orderShipmentItem/store', data)
}

/** [发货单明细模块] 获取发货单明细详情接口 */
export function orderShipmentItemShowApi(id: number): Promise<IOrderShipmentItemResponse> {
  return get<IOrderShipmentItemResponse>('admin/orderShipmentItem/show', { params: { id } })
}

/** [发货单明细模块] 更新发货单明细接口 */
export function orderShipmentItemUpdateApi(id: number, data: IOrderShipmentItemUpdateRequest): Promise<IOrderShipmentItemResponse> {
  return put<IOrderShipmentItemResponse>('admin/orderShipmentItem/update', data, { params: { id } })
}

/** [发货单明细模块] 删除发货单明细接口 */
export function orderShipmentItemDestroyApi(data: IOrderShipmentItemDestroyRequest): Promise<IOrderShipmentItemDestroyResponse> {
  return post<IOrderShipmentItemDestroyResponse>('admin/orderShipmentItem/destroy', data)
}
