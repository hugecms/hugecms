import { post, get, put } from '@/utils/request'
import type { IOrderRefundItemQueryRequest, IOrderRefundItemQueryResponse, IOrderRefundItemCreateRequest, IOrderRefundItemResponse, IOrderRefundItemUpdateRequest, IOrderRefundItemDestroyRequest, IOrderRefundItemDestroyResponse } from '@/types/orderRefundItem'

/** [退货明细模块] 查询退货明细列表接口 */
export function orderRefundItemSearchApi(page: number, pageSize: number, data: IOrderRefundItemQueryRequest): Promise<IOrderRefundItemQueryResponse> {
  return post<IOrderRefundItemQueryResponse>('admin/orderRefundItem/search', data, { params: { page, pageSize } })
}

/** [退货明细模块] 新增退货明细接口 */
export function orderRefundItemStoreApi(data: IOrderRefundItemCreateRequest): Promise<IOrderRefundItemResponse> {
  return post<IOrderRefundItemResponse>('admin/orderRefundItem/store', data)
}

/** [退货明细模块] 获取退货明细详情接口 */
export function orderRefundItemShowApi(id: number): Promise<IOrderRefundItemResponse> {
  return get<IOrderRefundItemResponse>('admin/orderRefundItem/show', { params: { id } })
}

/** [退货明细模块] 更新退货明细接口 */
export function orderRefundItemUpdateApi(id: number, data: IOrderRefundItemUpdateRequest): Promise<IOrderRefundItemResponse> {
  return put<IOrderRefundItemResponse>('admin/orderRefundItem/update', data, { params: { id } })
}

/** [退货明细模块] 删除退货明细接口 */
export function orderRefundItemDestroyApi(data: IOrderRefundItemDestroyRequest): Promise<IOrderRefundItemDestroyResponse> {
  return post<IOrderRefundItemDestroyResponse>('admin/orderRefundItem/destroy', data)
}
