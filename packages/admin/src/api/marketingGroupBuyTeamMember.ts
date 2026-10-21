import { post, get, put } from '@/utils/request'
import type { IMarketingGroupBuyTeamMemberQueryRequest, IMarketingGroupBuyTeamMemberQueryResponse, IMarketingGroupBuyTeamMemberCreateRequest, IMarketingGroupBuyTeamMemberResponse, IMarketingGroupBuyTeamMemberUpdateRequest, IMarketingGroupBuyTeamMemberDestroyRequest, IMarketingGroupBuyTeamMemberDestroyResponse } from '@/types/marketingGroupBuyTeamMember'

/** [拼团团员模块] 查询拼团团员列表接口 */
export function marketingGroupBuyTeamMemberSearchApi(page: number, pageSize: number, data: IMarketingGroupBuyTeamMemberQueryRequest): Promise<IMarketingGroupBuyTeamMemberQueryResponse> {
  return post<IMarketingGroupBuyTeamMemberQueryResponse>('admin/marketingGroupBuyTeamMember/search', data, { params: { page, pageSize } })
}

/** [拼团团员模块] 新增拼团团员接口 */
export function marketingGroupBuyTeamMemberStoreApi(data: IMarketingGroupBuyTeamMemberCreateRequest): Promise<IMarketingGroupBuyTeamMemberResponse> {
  return post<IMarketingGroupBuyTeamMemberResponse>('admin/marketingGroupBuyTeamMember/store', data)
}

/** [拼团团员模块] 获取拼团团员详情接口 */
export function marketingGroupBuyTeamMemberShowApi(id: number): Promise<IMarketingGroupBuyTeamMemberResponse> {
  return get<IMarketingGroupBuyTeamMemberResponse>('admin/marketingGroupBuyTeamMember/show', { params: { id } })
}

/** [拼团团员模块] 更新拼团团员接口 */
export function marketingGroupBuyTeamMemberUpdateApi(id: number, data: IMarketingGroupBuyTeamMemberUpdateRequest): Promise<IMarketingGroupBuyTeamMemberResponse> {
  return put<IMarketingGroupBuyTeamMemberResponse>('admin/marketingGroupBuyTeamMember/update', data, { params: { id } })
}

/** [拼团团员模块] 删除拼团团员接口 */
export function marketingGroupBuyTeamMemberDestroyApi(data: IMarketingGroupBuyTeamMemberDestroyRequest): Promise<IMarketingGroupBuyTeamMemberDestroyResponse> {
  return post<IMarketingGroupBuyTeamMemberDestroyResponse>('admin/marketingGroupBuyTeamMember/destroy', data)
}
