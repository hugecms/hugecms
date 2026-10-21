import { post, get, put } from '@/utils/request'
import type { ISiteAdQueryRequest, ISiteAdQueryResponse, ISiteAdCreateRequest, ISiteAdResponse, ISiteAdUpdateRequest, ISiteAdDestroyRequest, ISiteAdDestroyResponse } from '@/types/siteAd'

/** [广告模块] 查询广告列表接口 */
export function siteAdSearchApi(page: number, pageSize: number, data: ISiteAdQueryRequest): Promise<ISiteAdQueryResponse> {
  return post<ISiteAdQueryResponse>('admin/siteAd/search', data, { params: { page, pageSize } })
}

/** [广告模块] 新增广告接口 */
export function siteAdStoreApi(data: ISiteAdCreateRequest): Promise<ISiteAdResponse> {
  return post<ISiteAdResponse>('admin/siteAd/store', data)
}

/** [广告模块] 获取广告详情接口 */
export function siteAdShowApi(id: number): Promise<ISiteAdResponse> {
  return get<ISiteAdResponse>('admin/siteAd/show', { params: { id } })
}

/** [广告模块] 更新广告接口 */
export function siteAdUpdateApi(id: number, data: ISiteAdUpdateRequest): Promise<ISiteAdResponse> {
  return put<ISiteAdResponse>('admin/siteAd/update', data, { params: { id } })
}

/** [广告模块] 删除广告接口 */
export function siteAdDestroyApi(data: ISiteAdDestroyRequest): Promise<ISiteAdDestroyResponse> {
  return post<ISiteAdDestroyResponse>('admin/siteAd/destroy', data)
}
