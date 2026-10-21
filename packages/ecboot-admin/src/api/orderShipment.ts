import { post, get, put } from '@/utils/request'
import type { IOrderShipmentQueryRequest, IOrderShipmentQueryResponse, IOrderShipmentCreateRequest, IOrderShipmentResponse, IOrderShipmentUpdateRequest, IOrderShipmentDestroyRequest, IOrderShipmentDestroyResponse } from '@/types/orderShipment'

/** [发货单模块] 查询发货单列表接口 */
export function orderShipmentSearchApi(page: number, pageSize: number, data: IOrderShipmentQueryRequest): Promise<IOrderShipmentQueryResponse> {
  return post<IOrderShipmentQueryResponse>('admin/orderShipment/search', data, { params: { page, pageSize } })
}

/** [发货单模块] 新增发货单接口 */
export function orderShipmentStoreApi(data: IOrderShipmentCreateRequest): Promise<IOrderShipmentResponse> {
  return post<IOrderShipmentResponse>('admin/orderShipment/store', data)
}

/** [发货单模块] 获取发货单详情接口 */
export function orderShipmentShowApi(id: number): Promise<IOrderShipmentResponse> {
  return get<IOrderShipmentResponse>('admin/orderShipment/show', { params: { id } })
}

/** [发货单模块] 更新发货单接口 */
export function orderShipmentUpdateApi(id: number, data: IOrderShipmentUpdateRequest): Promise<IOrderShipmentResponse> {
  return put<IOrderShipmentResponse>('admin/orderShipment/update', data, { params: { id } })
}

/** [发货单模块] 删除发货单接口 */
export function orderShipmentDestroyApi(data: IOrderShipmentDestroyRequest): Promise<IOrderShipmentDestroyResponse> {
  return post<IOrderShipmentDestroyResponse>('admin/orderShipment/destroy', data)
}
