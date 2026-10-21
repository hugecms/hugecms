import { post, get, put } from '@/utils/request'
import type { IOrderRefundLogQueryRequest, IOrderRefundLogQueryResponse, IOrderRefundLogCreateRequest, IOrderRefundLogResponse, IOrderRefundLogUpdateRequest, IOrderRefundLogDestroyRequest, IOrderRefundLogDestroyResponse } from '@/types/orderRefundLog'

/** [售后协商日志模块] 查询售后协商日志列表接口 */
export function orderRefundLogSearchApi(page: number, pageSize: number, data: IOrderRefundLogQueryRequest): Promise<IOrderRefundLogQueryResponse> {
  return post<IOrderRefundLogQueryResponse>('admin/orderRefundLog/search', data, { params: { page, pageSize } })
}

/** [售后协商日志模块] 新增售后协商日志接口 */
export function orderRefundLogStoreApi(data: IOrderRefundLogCreateRequest): Promise<IOrderRefundLogResponse> {
  return post<IOrderRefundLogResponse>('admin/orderRefundLog/store', data)
}

/** [售后协商日志模块] 获取售后协商日志详情接口 */
export function orderRefundLogShowApi(id: number): Promise<IOrderRefundLogResponse> {
  return get<IOrderRefundLogResponse>('admin/orderRefundLog/show', { params: { id } })
}

/** [售后协商日志模块] 更新售后协商日志接口 */
export function orderRefundLogUpdateApi(id: number, data: IOrderRefundLogUpdateRequest): Promise<IOrderRefundLogResponse> {
  return put<IOrderRefundLogResponse>('admin/orderRefundLog/update', data, { params: { id } })
}

/** [售后协商日志模块] 删除售后协商日志接口 */
export function orderRefundLogDestroyApi(data: IOrderRefundLogDestroyRequest): Promise<IOrderRefundLogDestroyResponse> {
  return post<IOrderRefundLogDestroyResponse>('admin/orderRefundLog/destroy', data)
}
