import { post, get, put } from '@/utils/request'
import type { IMarketingPromotionScopeQueryRequest, IMarketingPromotionScopeQueryResponse, IMarketingPromotionScopeCreateRequest, IMarketingPromotionScopeResponse, IMarketingPromotionScopeUpdateRequest, IMarketingPromotionScopeDestroyRequest, IMarketingPromotionScopeDestroyResponse } from '@/types/marketingPromotionScope'

/** [促销适用范围模块] 查询促销适用范围列表接口 */
export function marketingPromotionScopeSearchApi(page: number, pageSize: number, data: IMarketingPromotionScopeQueryRequest): Promise<IMarketingPromotionScopeQueryResponse> {
  return post<IMarketingPromotionScopeQueryResponse>('admin/marketingPromotionScope/search', data, { params: { page, pageSize } })
}

/** [促销适用范围模块] 新增促销适用范围接口 */
export function marketingPromotionScopeStoreApi(data: IMarketingPromotionScopeCreateRequest): Promise<IMarketingPromotionScopeResponse> {
  return post<IMarketingPromotionScopeResponse>('admin/marketingPromotionScope/store', data)
}

/** [促销适用范围模块] 获取促销适用范围详情接口 */
export function marketingPromotionScopeShowApi(id: number): Promise<IMarketingPromotionScopeResponse> {
  return get<IMarketingPromotionScopeResponse>('admin/marketingPromotionScope/show', { params: { id } })
}

/** [促销适用范围模块] 更新促销适用范围接口 */
export function marketingPromotionScopeUpdateApi(id: number, data: IMarketingPromotionScopeUpdateRequest): Promise<IMarketingPromotionScopeResponse> {
  return put<IMarketingPromotionScopeResponse>('admin/marketingPromotionScope/update', data, { params: { id } })
}

/** [促销适用范围模块] 删除促销适用范围接口 */
export function marketingPromotionScopeDestroyApi(data: IMarketingPromotionScopeDestroyRequest): Promise<IMarketingPromotionScopeDestroyResponse> {
  return post<IMarketingPromotionScopeDestroyResponse>('admin/marketingPromotionScope/destroy', data)
}
