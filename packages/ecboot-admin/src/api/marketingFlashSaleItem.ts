import { post, get, put } from '@/utils/request'
import type { IMarketingFlashSaleItemQueryRequest, IMarketingFlashSaleItemQueryResponse, IMarketingFlashSaleItemCreateRequest, IMarketingFlashSaleItemResponse, IMarketingFlashSaleItemUpdateRequest, IMarketingFlashSaleItemDestroyRequest, IMarketingFlashSaleItemDestroyResponse } from '@/types/marketingFlashSaleItem'

/** [秒杀商品模块] 查询秒杀商品列表接口 */
export function marketingFlashSaleItemSearchApi(page: number, pageSize: number, data: IMarketingFlashSaleItemQueryRequest): Promise<IMarketingFlashSaleItemQueryResponse> {
  return post<IMarketingFlashSaleItemQueryResponse>('admin/marketingFlashSaleItem/search', data, { params: { page, pageSize } })
}

/** [秒杀商品模块] 新增秒杀商品接口 */
export function marketingFlashSaleItemStoreApi(data: IMarketingFlashSaleItemCreateRequest): Promise<IMarketingFlashSaleItemResponse> {
  return post<IMarketingFlashSaleItemResponse>('admin/marketingFlashSaleItem/store', data)
}

/** [秒杀商品模块] 获取秒杀商品详情接口 */
export function marketingFlashSaleItemShowApi(id: number): Promise<IMarketingFlashSaleItemResponse> {
  return get<IMarketingFlashSaleItemResponse>('admin/marketingFlashSaleItem/show', { params: { id } })
}

/** [秒杀商品模块] 更新秒杀商品接口 */
export function marketingFlashSaleItemUpdateApi(id: number, data: IMarketingFlashSaleItemUpdateRequest): Promise<IMarketingFlashSaleItemResponse> {
  return put<IMarketingFlashSaleItemResponse>('admin/marketingFlashSaleItem/update', data, { params: { id } })
}

/** [秒杀商品模块] 删除秒杀商品接口 */
export function marketingFlashSaleItemDestroyApi(data: IMarketingFlashSaleItemDestroyRequest): Promise<IMarketingFlashSaleItemDestroyResponse> {
  return post<IMarketingFlashSaleItemDestroyResponse>('admin/marketingFlashSaleItem/destroy', data)
}
