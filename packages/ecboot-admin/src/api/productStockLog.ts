import { post, get, put } from '@/utils/request'
import type { IProductStockLogQueryRequest, IProductStockLogQueryResponse, IProductStockLogCreateRequest, IProductStockLogResponse, IProductStockLogUpdateRequest, IProductStockLogDestroyRequest, IProductStockLogDestroyResponse } from '@/types/productStockLog'

/** [库存流水模块] 查询库存流水列表接口 */
export function productStockLogSearchApi(page: number, pageSize: number, data: IProductStockLogQueryRequest): Promise<IProductStockLogQueryResponse> {
  return post<IProductStockLogQueryResponse>('admin/productStockLog/search', data, { params: { page, pageSize } })
}

/** [库存流水模块] 新增库存流水接口 */
export function productStockLogStoreApi(data: IProductStockLogCreateRequest): Promise<IProductStockLogResponse> {
  return post<IProductStockLogResponse>('admin/productStockLog/store', data)
}

/** [库存流水模块] 获取库存流水详情接口 */
export function productStockLogShowApi(id: number): Promise<IProductStockLogResponse> {
  return get<IProductStockLogResponse>('admin/productStockLog/show', { params: { id } })
}

/** [库存流水模块] 更新库存流水接口 */
export function productStockLogUpdateApi(id: number, data: IProductStockLogUpdateRequest): Promise<IProductStockLogResponse> {
  return put<IProductStockLogResponse>('admin/productStockLog/update', data, { params: { id } })
}

/** [库存流水模块] 删除库存流水接口 */
export function productStockLogDestroyApi(data: IProductStockLogDestroyRequest): Promise<IProductStockLogDestroyResponse> {
  return post<IProductStockLogDestroyResponse>('admin/productStockLog/destroy', data)
}
