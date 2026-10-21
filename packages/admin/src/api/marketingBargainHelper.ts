import { post, get, put } from '@/utils/request'
import type { IMarketingBargainHelperQueryRequest, IMarketingBargainHelperQueryResponse, IMarketingBargainHelperCreateRequest, IMarketingBargainHelperResponse, IMarketingBargainHelperUpdateRequest, IMarketingBargainHelperDestroyRequest, IMarketingBargainHelperDestroyResponse } from '@/types/marketingBargainHelper'

/** [帮砍记录模块] 查询帮砍记录列表接口 */
export function marketingBargainHelperSearchApi(page: number, pageSize: number, data: IMarketingBargainHelperQueryRequest): Promise<IMarketingBargainHelperQueryResponse> {
  return post<IMarketingBargainHelperQueryResponse>('admin/marketingBargainHelper/search', data, { params: { page, pageSize } })
}

/** [帮砍记录模块] 新增帮砍记录接口 */
export function marketingBargainHelperStoreApi(data: IMarketingBargainHelperCreateRequest): Promise<IMarketingBargainHelperResponse> {
  return post<IMarketingBargainHelperResponse>('admin/marketingBargainHelper/store', data)
}

/** [帮砍记录模块] 获取帮砍记录详情接口 */
export function marketingBargainHelperShowApi(id: number): Promise<IMarketingBargainHelperResponse> {
  return get<IMarketingBargainHelperResponse>('admin/marketingBargainHelper/show', { params: { id } })
}

/** [帮砍记录模块] 更新帮砍记录接口 */
export function marketingBargainHelperUpdateApi(id: number, data: IMarketingBargainHelperUpdateRequest): Promise<IMarketingBargainHelperResponse> {
  return put<IMarketingBargainHelperResponse>('admin/marketingBargainHelper/update', data, { params: { id } })
}

/** [帮砍记录模块] 删除帮砍记录接口 */
export function marketingBargainHelperDestroyApi(data: IMarketingBargainHelperDestroyRequest): Promise<IMarketingBargainHelperDestroyResponse> {
  return post<IMarketingBargainHelperDestroyResponse>('admin/marketingBargainHelper/destroy', data)
}
