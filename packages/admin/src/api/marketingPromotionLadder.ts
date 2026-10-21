import { post, get, put } from '@/utils/request'
import type { IMarketingPromotionLadderQueryRequest, IMarketingPromotionLadderQueryResponse, IMarketingPromotionLadderCreateRequest, IMarketingPromotionLadderResponse, IMarketingPromotionLadderUpdateRequest, IMarketingPromotionLadderDestroyRequest, IMarketingPromotionLadderDestroyResponse } from '@/types/marketingPromotionLadder'

/** [促销满减阶梯模块] 查询促销满减阶梯列表接口 */
export function marketingPromotionLadderSearchApi(page: number, pageSize: number, data: IMarketingPromotionLadderQueryRequest): Promise<IMarketingPromotionLadderQueryResponse> {
  return post<IMarketingPromotionLadderQueryResponse>('admin/marketingPromotionLadder/search', data, { params: { page, pageSize } })
}

/** [促销满减阶梯模块] 新增促销满减阶梯接口 */
export function marketingPromotionLadderStoreApi(data: IMarketingPromotionLadderCreateRequest): Promise<IMarketingPromotionLadderResponse> {
  return post<IMarketingPromotionLadderResponse>('admin/marketingPromotionLadder/store', data)
}

/** [促销满减阶梯模块] 获取促销满减阶梯详情接口 */
export function marketingPromotionLadderShowApi(id: number): Promise<IMarketingPromotionLadderResponse> {
  return get<IMarketingPromotionLadderResponse>('admin/marketingPromotionLadder/show', { params: { id } })
}

/** [促销满减阶梯模块] 更新促销满减阶梯接口 */
export function marketingPromotionLadderUpdateApi(id: number, data: IMarketingPromotionLadderUpdateRequest): Promise<IMarketingPromotionLadderResponse> {
  return put<IMarketingPromotionLadderResponse>('admin/marketingPromotionLadder/update', data, { params: { id } })
}

/** [促销满减阶梯模块] 删除促销满减阶梯接口 */
export function marketingPromotionLadderDestroyApi(data: IMarketingPromotionLadderDestroyRequest): Promise<IMarketingPromotionLadderDestroyResponse> {
  return post<IMarketingPromotionLadderDestroyResponse>('admin/marketingPromotionLadder/destroy', data)
}
