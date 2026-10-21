import { post, get, put } from '@/utils/request'
import type { IOrderLogQueryRequest, IOrderLogQueryResponse, IOrderLogCreateRequest, IOrderLogResponse, IOrderLogUpdateRequest, IOrderLogDestroyRequest, IOrderLogDestroyResponse } from '@/types/orderLog'

/** [订单操作日志模块] 查询订单操作日志列表接口 */
export function orderLogSearchApi(page: number, pageSize: number, data: IOrderLogQueryRequest): Promise<IOrderLogQueryResponse> {
  return post<IOrderLogQueryResponse>('admin/orderLog/search', data, { params: { page, pageSize } })
}

/** [订单操作日志模块] 新增订单操作日志接口 */
export function orderLogStoreApi(data: IOrderLogCreateRequest): Promise<IOrderLogResponse> {
  return post<IOrderLogResponse>('admin/orderLog/store', data)
}

/** [订单操作日志模块] 获取订单操作日志详情接口 */
export function orderLogShowApi(id: number): Promise<IOrderLogResponse> {
  return get<IOrderLogResponse>('admin/orderLog/show', { params: { id } })
}

/** [订单操作日志模块] 更新订单操作日志接口 */
export function orderLogUpdateApi(id: number, data: IOrderLogUpdateRequest): Promise<IOrderLogResponse> {
  return put<IOrderLogResponse>('admin/orderLog/update', data, { params: { id } })
}

/** [订单操作日志模块] 删除订单操作日志接口 */
export function orderLogDestroyApi(data: IOrderLogDestroyRequest): Promise<IOrderLogDestroyResponse> {
  return post<IOrderLogDestroyResponse>('admin/orderLog/destroy', data)
}
