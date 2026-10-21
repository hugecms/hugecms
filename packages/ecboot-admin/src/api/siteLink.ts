import { post, get, put } from '@/utils/request'
import type { ISiteLinkQueryRequest, ISiteLinkQueryResponse, ISiteLinkCreateRequest, ISiteLinkResponse, ISiteLinkUpdateRequest, ISiteLinkDestroyRequest, ISiteLinkDestroyResponse } from '@/types/siteLink'

/** [友情链接模块] 查询友情链接列表接口 */
export function siteLinkSearchApi(page: number, pageSize: number, data: ISiteLinkQueryRequest): Promise<ISiteLinkQueryResponse> {
  return post<ISiteLinkQueryResponse>('admin/siteLink/search', data, { params: { page, pageSize } })
}

/** [友情链接模块] 新增友情链接接口 */
export function siteLinkStoreApi(data: ISiteLinkCreateRequest): Promise<ISiteLinkResponse> {
  return post<ISiteLinkResponse>('admin/siteLink/store', data)
}

/** [友情链接模块] 获取友情链接详情接口 */
export function siteLinkShowApi(id: number): Promise<ISiteLinkResponse> {
  return get<ISiteLinkResponse>('admin/siteLink/show', { params: { id } })
}

/** [友情链接模块] 更新友情链接接口 */
export function siteLinkUpdateApi(id: number, data: ISiteLinkUpdateRequest): Promise<ISiteLinkResponse> {
  return put<ISiteLinkResponse>('admin/siteLink/update', data, { params: { id } })
}

/** [友情链接模块] 删除友情链接接口 */
export function siteLinkDestroyApi(data: ISiteLinkDestroyRequest): Promise<ISiteLinkDestroyResponse> {
  return post<ISiteLinkDestroyResponse>('admin/siteLink/destroy', data)
}
