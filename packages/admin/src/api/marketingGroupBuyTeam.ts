import { post, get, put } from '@/utils/request'
import type { IMarketingGroupBuyTeamQueryRequest, IMarketingGroupBuyTeamQueryResponse, IMarketingGroupBuyTeamCreateRequest, IMarketingGroupBuyTeamResponse, IMarketingGroupBuyTeamUpdateRequest, IMarketingGroupBuyTeamDestroyRequest, IMarketingGroupBuyTeamDestroyResponse } from '@/types/marketingGroupBuyTeam'

/** [拼团团模块] 查询拼团团列表接口 */
export function marketingGroupBuyTeamSearchApi(page: number, pageSize: number, data: IMarketingGroupBuyTeamQueryRequest): Promise<IMarketingGroupBuyTeamQueryResponse> {
  return post<IMarketingGroupBuyTeamQueryResponse>('admin/marketingGroupBuyTeam/search', data, { params: { page, pageSize } })
}

/** [拼团团模块] 新增拼团团接口 */
export function marketingGroupBuyTeamStoreApi(data: IMarketingGroupBuyTeamCreateRequest): Promise<IMarketingGroupBuyTeamResponse> {
  return post<IMarketingGroupBuyTeamResponse>('admin/marketingGroupBuyTeam/store', data)
}

/** [拼团团模块] 获取拼团团详情接口 */
export function marketingGroupBuyTeamShowApi(id: number): Promise<IMarketingGroupBuyTeamResponse> {
  return get<IMarketingGroupBuyTeamResponse>('admin/marketingGroupBuyTeam/show', { params: { id } })
}

/** [拼团团模块] 更新拼团团接口 */
export function marketingGroupBuyTeamUpdateApi(id: number, data: IMarketingGroupBuyTeamUpdateRequest): Promise<IMarketingGroupBuyTeamResponse> {
  return put<IMarketingGroupBuyTeamResponse>('admin/marketingGroupBuyTeam/update', data, { params: { id } })
}

/** [拼团团模块] 删除拼团团接口 */
export function marketingGroupBuyTeamDestroyApi(data: IMarketingGroupBuyTeamDestroyRequest): Promise<IMarketingGroupBuyTeamDestroyResponse> {
  return post<IMarketingGroupBuyTeamDestroyResponse>('admin/marketingGroupBuyTeam/destroy', data)
}
