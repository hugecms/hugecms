import { post, get, put } from '@/utils/request'
import type { IMarketingFlashSaleLogQueryRequest, IMarketingFlashSaleLogQueryResponse, IMarketingFlashSaleLogCreateRequest, IMarketingFlashSaleLogResponse, IMarketingFlashSaleLogUpdateRequest, IMarketingFlashSaleLogDestroyRequest, IMarketingFlashSaleLogDestroyResponse } from '@/types/marketingFlashSaleLog'

/** [秒杀库存流水模块] 查询秒杀库存流水列表接口 */
export function marketingFlashSaleLogSearchApi(page: number, pageSize: number, data: IMarketingFlashSaleLogQueryRequest): Promise<IMarketingFlashSaleLogQueryResponse> {
  return post<IMarketingFlashSaleLogQueryResponse>('admin/marketingFlashSaleLog/search', data, { params: { page, pageSize } })
}

/** [秒杀库存流水模块] 新增秒杀库存流水接口 */
export function marketingFlashSaleLogStoreApi(data: IMarketingFlashSaleLogCreateRequest): Promise<IMarketingFlashSaleLogResponse> {
  return post<IMarketingFlashSaleLogResponse>('admin/marketingFlashSaleLog/store', data)
}

/** [秒杀库存流水模块] 获取秒杀库存流水详情接口 */
export function marketingFlashSaleLogShowApi(id: number): Promise<IMarketingFlashSaleLogResponse> {
  return get<IMarketingFlashSaleLogResponse>('admin/marketingFlashSaleLog/show', { params: { id } })
}

/** [秒杀库存流水模块] 更新秒杀库存流水接口 */
export function marketingFlashSaleLogUpdateApi(id: number, data: IMarketingFlashSaleLogUpdateRequest): Promise<IMarketingFlashSaleLogResponse> {
  return put<IMarketingFlashSaleLogResponse>('admin/marketingFlashSaleLog/update', data, { params: { id } })
}

/** [秒杀库存流水模块] 删除秒杀库存流水接口 */
export function marketingFlashSaleLogDestroyApi(data: IMarketingFlashSaleLogDestroyRequest): Promise<IMarketingFlashSaleLogDestroyResponse> {
  return post<IMarketingFlashSaleLogDestroyResponse>('admin/marketingFlashSaleLog/destroy', data)
}
