import { post, get, put } from '@/utils/request'
import type { IMarketingFlashSaleQueryRequest, IMarketingFlashSaleQueryResponse, IMarketingFlashSaleCreateRequest, IMarketingFlashSaleResponse, IMarketingFlashSaleUpdateRequest, IMarketingFlashSaleDestroyRequest, IMarketingFlashSaleDestroyResponse } from '@/types/marketingFlashSale'

/** [秒杀活�模块] 查询秒杀活�列表接口 */
export function marketingFlashSaleSearchApi(page: number, pageSize: number, data: IMarketingFlashSaleQueryRequest): Promise<IMarketingFlashSaleQueryResponse> {
  return post<IMarketingFlashSaleQueryResponse>('admin/marketingFlashSale/search', data, { params: { page, pageSize } })
}

/** [秒杀活�模块] 新增秒杀活�接口 */
export function marketingFlashSaleStoreApi(data: IMarketingFlashSaleCreateRequest): Promise<IMarketingFlashSaleResponse> {
  return post<IMarketingFlashSaleResponse>('admin/marketingFlashSale/store', data)
}

/** [秒杀活�模块] 获取秒杀活�详情接口 */
export function marketingFlashSaleShowApi(id: number): Promise<IMarketingFlashSaleResponse> {
  return get<IMarketingFlashSaleResponse>('admin/marketingFlashSale/show', { params: { id } })
}

/** [秒杀活�模块] 更新秒杀活�接口 */
export function marketingFlashSaleUpdateApi(id: number, data: IMarketingFlashSaleUpdateRequest): Promise<IMarketingFlashSaleResponse> {
  return put<IMarketingFlashSaleResponse>('admin/marketingFlashSale/update', data, { params: { id } })
}

/** [秒杀活�模块] 删除秒杀活�接口 */
export function marketingFlashSaleDestroyApi(data: IMarketingFlashSaleDestroyRequest): Promise<IMarketingFlashSaleDestroyResponse> {
  return post<IMarketingFlashSaleDestroyResponse>('admin/marketingFlashSale/destroy', data)
}
