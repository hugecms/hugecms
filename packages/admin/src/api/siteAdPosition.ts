import { post, get, put } from '@/utils/request'
import type { ISiteAdPositionQueryRequest, ISiteAdPositionQueryResponse, ISiteAdPositionCreateRequest, ISiteAdPositionResponse, ISiteAdPositionUpdateRequest, ISiteAdPositionDestroyRequest, ISiteAdPositionDestroyResponse } from '@/types/siteAdPosition'

/** [广告位定义模块] 查询广告位定义列表接口 */
export function siteAdPositionSearchApi(page: number, pageSize: number, data: ISiteAdPositionQueryRequest): Promise<ISiteAdPositionQueryResponse> {
  return post<ISiteAdPositionQueryResponse>('admin/siteAdPosition/search', data, { params: { page, pageSize } })
}

/** [广告位定义模块] 新增广告位定义接口 */
export function siteAdPositionStoreApi(data: ISiteAdPositionCreateRequest): Promise<ISiteAdPositionResponse> {
  return post<ISiteAdPositionResponse>('admin/siteAdPosition/store', data)
}

/** [广告位定义模块] 获取广告位定义详情接口 */
export function siteAdPositionShowApi(id: number): Promise<ISiteAdPositionResponse> {
  return get<ISiteAdPositionResponse>('admin/siteAdPosition/show', { params: { id } })
}

/** [广告位定义模块] 更新广告位定义接口 */
export function siteAdPositionUpdateApi(id: number, data: ISiteAdPositionUpdateRequest): Promise<ISiteAdPositionResponse> {
  return put<ISiteAdPositionResponse>('admin/siteAdPosition/update', data, { params: { id } })
}

/** [广告位定义模块] 删除广告位定义接口 */
export function siteAdPositionDestroyApi(data: ISiteAdPositionDestroyRequest): Promise<ISiteAdPositionDestroyResponse> {
  return post<ISiteAdPositionDestroyResponse>('admin/siteAdPosition/destroy', data)
}
