import { post, get, put } from '@/utils/request'
import type { IMarketingPromotionQueryRequest, IMarketingPromotionQueryResponse, IMarketingPromotionCreateRequest, IMarketingPromotionResponse, IMarketingPromotionUpdateRequest, IMarketingPromotionDestroyRequest, IMarketingPromotionDestroyResponse } from '@/types/marketingPromotion'

/** [促销活�模块] 查询促销活�列表接口 */
export function marketingPromotionSearchApi(page: number, pageSize: number, data: IMarketingPromotionQueryRequest): Promise<IMarketingPromotionQueryResponse> {
  return post<IMarketingPromotionQueryResponse>('admin/marketingPromotion/search', data, { params: { page, pageSize } })
}

/** [促销活�模块] 新增促销活�接口 */
export function marketingPromotionStoreApi(data: IMarketingPromotionCreateRequest): Promise<IMarketingPromotionResponse> {
  return post<IMarketingPromotionResponse>('admin/marketingPromotion/store', data)
}

/** [促销活�模块] 获取促销活�详情接口 */
export function marketingPromotionShowApi(id: number): Promise<IMarketingPromotionResponse> {
  return get<IMarketingPromotionResponse>('admin/marketingPromotion/show', { params: { id } })
}

/** [促销活�模块] 更新促销活�接口 */
export function marketingPromotionUpdateApi(id: number, data: IMarketingPromotionUpdateRequest): Promise<IMarketingPromotionResponse> {
  return put<IMarketingPromotionResponse>('admin/marketingPromotion/update', data, { params: { id } })
}

/** [促销活�模块] 删除促销活�接口 */
export function marketingPromotionDestroyApi(data: IMarketingPromotionDestroyRequest): Promise<IMarketingPromotionDestroyResponse> {
  return post<IMarketingPromotionDestroyResponse>('admin/marketingPromotion/destroy', data)
}
