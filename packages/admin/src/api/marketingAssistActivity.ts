import { post, get, put } from '@/utils/request'
import type { IMarketingAssistActivityQueryRequest, IMarketingAssistActivityQueryResponse, IMarketingAssistActivityCreateRequest, IMarketingAssistActivityResponse, IMarketingAssistActivityUpdateRequest, IMarketingAssistActivityDestroyRequest, IMarketingAssistActivityDestroyResponse } from '@/types/marketingAssistActivity'

/** [助力活�模块] 查询助力活�列表接口 */
export function marketingAssistActivitySearchApi(page: number, pageSize: number, data: IMarketingAssistActivityQueryRequest): Promise<IMarketingAssistActivityQueryResponse> {
  return post<IMarketingAssistActivityQueryResponse>('admin/marketingAssistActivity/search', data, { params: { page, pageSize } })
}

/** [助力活�模块] 新增助力活�接口 */
export function marketingAssistActivityStoreApi(data: IMarketingAssistActivityCreateRequest): Promise<IMarketingAssistActivityResponse> {
  return post<IMarketingAssistActivityResponse>('admin/marketingAssistActivity/store', data)
}

/** [助力活�模块] 获取助力活�详情接口 */
export function marketingAssistActivityShowApi(id: number): Promise<IMarketingAssistActivityResponse> {
  return get<IMarketingAssistActivityResponse>('admin/marketingAssistActivity/show', { params: { id } })
}

/** [助力活�模块] 更新助力活�接口 */
export function marketingAssistActivityUpdateApi(id: number, data: IMarketingAssistActivityUpdateRequest): Promise<IMarketingAssistActivityResponse> {
  return put<IMarketingAssistActivityResponse>('admin/marketingAssistActivity/update', data, { params: { id } })
}

/** [助力活�模块] 删除助力活�接口 */
export function marketingAssistActivityDestroyApi(data: IMarketingAssistActivityDestroyRequest): Promise<IMarketingAssistActivityDestroyResponse> {
  return post<IMarketingAssistActivityDestroyResponse>('admin/marketingAssistActivity/destroy', data)
}
