import { post, get, put } from '@/utils/request'
import type { IMarketingAssistHelperQueryRequest, IMarketingAssistHelperQueryResponse, IMarketingAssistHelperCreateRequest, IMarketingAssistHelperResponse, IMarketingAssistHelperUpdateRequest, IMarketingAssistHelperDestroyRequest, IMarketingAssistHelperDestroyResponse } from '@/types/marketingAssistHelper'

/** [助力人记录模块] 查询助力人记录列表接口 */
export function marketingAssistHelperSearchApi(page: number, pageSize: number, data: IMarketingAssistHelperQueryRequest): Promise<IMarketingAssistHelperQueryResponse> {
  return post<IMarketingAssistHelperQueryResponse>('admin/marketingAssistHelper/search', data, { params: { page, pageSize } })
}

/** [助力人记录模块] 新增助力人记录接口 */
export function marketingAssistHelperStoreApi(data: IMarketingAssistHelperCreateRequest): Promise<IMarketingAssistHelperResponse> {
  return post<IMarketingAssistHelperResponse>('admin/marketingAssistHelper/store', data)
}

/** [助力人记录模块] 获取助力人记录详情接口 */
export function marketingAssistHelperShowApi(id: number): Promise<IMarketingAssistHelperResponse> {
  return get<IMarketingAssistHelperResponse>('admin/marketingAssistHelper/show', { params: { id } })
}

/** [助力人记录模块] 更新助力人记录接口 */
export function marketingAssistHelperUpdateApi(id: number, data: IMarketingAssistHelperUpdateRequest): Promise<IMarketingAssistHelperResponse> {
  return put<IMarketingAssistHelperResponse>('admin/marketingAssistHelper/update', data, { params: { id } })
}

/** [助力人记录模块] 删除助力人记录接口 */
export function marketingAssistHelperDestroyApi(data: IMarketingAssistHelperDestroyRequest): Promise<IMarketingAssistHelperDestroyResponse> {
  return post<IMarketingAssistHelperDestroyResponse>('admin/marketingAssistHelper/destroy', data)
}
