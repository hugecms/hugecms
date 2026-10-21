import { post, get, put } from '@/utils/request'
import type { IMarketingGroupBuyActivityQueryRequest, IMarketingGroupBuyActivityQueryResponse, IMarketingGroupBuyActivityCreateRequest, IMarketingGroupBuyActivityResponse, IMarketingGroupBuyActivityUpdateRequest, IMarketingGroupBuyActivityDestroyRequest, IMarketingGroupBuyActivityDestroyResponse } from '@/types/marketingGroupBuyActivity'

/** [拼团活�模块] 查询拼团活�列表接口 */
export function marketingGroupBuyActivitySearchApi(page: number, pageSize: number, data: IMarketingGroupBuyActivityQueryRequest): Promise<IMarketingGroupBuyActivityQueryResponse> {
  return post<IMarketingGroupBuyActivityQueryResponse>('admin/marketingGroupBuyActivity/search', data, { params: { page, pageSize } })
}

/** [拼团活�模块] 新增拼团活�接口 */
export function marketingGroupBuyActivityStoreApi(data: IMarketingGroupBuyActivityCreateRequest): Promise<IMarketingGroupBuyActivityResponse> {
  return post<IMarketingGroupBuyActivityResponse>('admin/marketingGroupBuyActivity/store', data)
}

/** [拼团活�模块] 获取拼团活�详情接口 */
export function marketingGroupBuyActivityShowApi(id: number): Promise<IMarketingGroupBuyActivityResponse> {
  return get<IMarketingGroupBuyActivityResponse>('admin/marketingGroupBuyActivity/show', { params: { id } })
}

/** [拼团活�模块] 更新拼团活�接口 */
export function marketingGroupBuyActivityUpdateApi(id: number, data: IMarketingGroupBuyActivityUpdateRequest): Promise<IMarketingGroupBuyActivityResponse> {
  return put<IMarketingGroupBuyActivityResponse>('admin/marketingGroupBuyActivity/update', data, { params: { id } })
}

/** [拼团活�模块] 删除拼团活�接口 */
export function marketingGroupBuyActivityDestroyApi(data: IMarketingGroupBuyActivityDestroyRequest): Promise<IMarketingGroupBuyActivityDestroyResponse> {
  return post<IMarketingGroupBuyActivityDestroyResponse>('admin/marketingGroupBuyActivity/destroy', data)
}
