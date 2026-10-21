import { post, get, put } from '@/utils/request'
import type { IOrderRefundQueryRequest, IOrderRefundQueryResponse, IOrderRefundCreateRequest, IOrderRefundResponse, IOrderRefundUpdateRequest, IOrderRefundDestroyRequest, IOrderRefundDestroyResponse } from '@/types/orderRefund'

/** [退款单模块] 查询退款单列表接口 */
export function orderRefundSearchApi(page: number, pageSize: number, data: IOrderRefundQueryRequest): Promise<IOrderRefundQueryResponse> {
  return post<IOrderRefundQueryResponse>('admin/orderRefund/search', data, { params: { page, pageSize } })
}

/** [退款单模块] 新增退款单接口 */
export function orderRefundStoreApi(data: IOrderRefundCreateRequest): Promise<IOrderRefundResponse> {
  return post<IOrderRefundResponse>('admin/orderRefund/store', data)
}

/** [退款单模块] 获取退款单详情接口 */
export function orderRefundShowApi(id: number): Promise<IOrderRefundResponse> {
  return get<IOrderRefundResponse>('admin/orderRefund/show', { params: { id } })
}

/** [退款单模块] 更新退款单接口 */
export function orderRefundUpdateApi(id: number, data: IOrderRefundUpdateRequest): Promise<IOrderRefundResponse> {
  return put<IOrderRefundResponse>('admin/orderRefund/update', data, { params: { id } })
}

/** [退款单模块] 删除退款单接口 */
export function orderRefundDestroyApi(data: IOrderRefundDestroyRequest): Promise<IOrderRefundDestroyResponse> {
  return post<IOrderRefundDestroyResponse>('admin/orderRefund/destroy', data)
}
